#!/usr/bin/env python3
import argparse
import json
import os
import re
import sys
import cv2
import numpy as np
import pytesseract
from pytesseract import Output

# Hardcoded tesseract per your environment
pytesseract.pytesseract.tesseract_cmd = r'D:\tessaract\tesseract.exe'

IMAGE_DIR = './storage/app/public/imei-images/'

# optional barcode lib — ignore if missing
try:
    from pyzbar.pyzbar import decode as pyzbar_decode
    _PYZBAR_AVAILABLE = True
except Exception:
    _PYZBAR_AVAILABLE = False
    pyzbar_decode = None

# ========== Utility functions ==========
def safe_print_json(imeis):
    try:
        print(json.dumps({"imei": imeis}))
    except Exception:
        print('{"imei": []}')

def luhn_ok(s: str) -> bool:
    if not re.fullmatch(r'\d{15}', s):
        return False
    digits = [int(d) for d in s]
    total = 0
    for j in range(15):
        d = digits[14 - j]
        if j % 2 == 1:
            d *= 2
            if d > 9:
                d -= 9
        total += d
    return total % 10 == 0

def limit_max_dim(img, max_dim=1600):
    h, w = img.shape[:2]
    if max(h, w) > max_dim:
        scale = max_dim / float(max(h, w))
        img = cv2.resize(img, None, fx=scale, fy=scale, interpolation=cv2.INTER_AREA)
    return img

# ========== OCR helpers ==========
def run_tesseract_image_to_data(img_gray, config=None):
    try:
        cfg = config or r'--oem 3 --psm 6 -c tessedit_char_whitelist=0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz:- '
        data = pytesseract.image_to_data(img_gray, config=cfg, output_type=Output.DICT)
        return data
    except Exception:
        return None

def collect_tokens_from_data(data):
    tokens = []
    if not data:
        return tokens
    texts = data.get('text', [])
    n = len(texts)
    for i in range(n):
        txt = (texts[i] or "").strip()
        if not txt:
            continue
        try:
            conf = int(float(data.get('conf', [])[i]))
        except Exception:
            conf = -1
        try:
            left = int(data.get('left', [0]*n)[i])
            top = int(data.get('top', [0]*n)[i])
            w = int(data.get('width', [0]*n)[i])
            h = int(data.get('height', [0]*n)[i])
        except Exception:
            left = top = w = h = 0
        tokens.append({
            'text': txt,
            'digits': re.sub(r'\D', '', txt),
            'conf': conf,
            'x': left,
            'y': top,
            'w': w,
            'h': h
        })
    return tokens

def remove_bars_lines(img_gray):
    try:
        _, bw = cv2.threshold(img_gray, 0, 255, cv2.THRESH_BINARY | cv2.THRESH_OTSU)
        vert_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, 30))
        bars_vert = cv2.morphologyEx(bw, cv2.MORPH_OPEN, vert_kernel, iterations=1)
        hor_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (40, 3))
        bars_hor = cv2.morphologyEx(bw, cv2.MORPH_OPEN, hor_kernel, iterations=1)
        mask = cv2.dilate(cv2.bitwise_or(bars_vert, bars_hor), cv2.getStructuringElement(cv2.MORPH_RECT, (3,3)), iterations=2)
        if np.count_nonzero(mask) == 0:
            return img_gray
        return cv2.inpaint(img_gray, mask, 3, cv2.INPAINT_TELEA)
    except Exception:
        return img_gray

# ========== Candidate extraction functions ==========
def group_tokens_lines(tokens, y_tol=12):
    lines = []
    for t in sorted(tokens, key=lambda x: (x['y'], x['x'])):
        placed = False
        for L in lines:
            if abs(t['y'] - L['y_mean']) <= y_tol:
                L['tokens'].append(t)
                L['y_values'].append(t['y'])
                L['y_mean'] = sum(L['y_values']) / len(L['y_values'])
                placed = True
                break
        if not placed:
            lines.append({'y_mean': t['y'], 'tokens':[t], 'y_values':[t['y']]})
    return lines

def best_window_for_merged_block(tokens_block):
    """
    tokens_block: list of tokens in order that have 'digits' and 'conf'.
    Return single best 15-digit window (imei, score) or None.
    Score ~ weighted average token conf (weighted by overlap in window).
    """
    # build digits concat and token digit boundaries
    digits_pieces = [t['digits'] for t in tokens_block]
    if not any(digits_pieces):
        return None
    lens = [len(p) for p in digits_pieces]
    total_digits = sum(lens)
    if total_digits < 15:
        return None
    # prefix sums
    prefix = [0]
    for L in lens:
        prefix.append(prefix[-1]+L)
    concat = "".join(digits_pieces)
    best = None  # (score, imei)
    # For each possible 15-window
    for start in range(0, total_digits - 15 + 1):
        end = start + 15  # non-inclusive
        # compute token overlap weights
        score_num = 0.0
        score_denom = 0.0
        # find tokens overlapping [start,end)
        for i, t in enumerate(tokens_block):
            t_start = prefix[i]
            t_end = prefix[i+1]
            overlap_start = max(start, t_start)
            overlap_end = min(end, t_end)
            overlap = max(0, overlap_end - overlap_start)
            if overlap <= 0:
                continue
            conf = t.get('conf', 40)
            score_num += conf * overlap
            score_denom += overlap
        if score_denom == 0:
            avg_conf = 0
        else:
            avg_conf = score_num / score_denom
        imei_candidate = concat[start:end]
        # simple compactness penalty: fewer tokens contributing -> slight boost
        tokens_used = sum(1 for i in range(len(tokens_block)) if not (prefix[i+1] <= start or prefix[i] >= end))
        compact_bonus = max(0, 5 - tokens_used)  # prefer <=5 tokens
        score = avg_conf + compact_bonus
        # pick best
        if best is None or score > best[0]:
            best = (score, imei_candidate)
    if best:
        return (best[1], best[0])
    return None

def extract_from_tokens_strategy(img_color):
    """
    Token merging strategy returns list of (imei, score, 'tokens').
    We pick only one candidate per merged block (best window) to avoid overlapping permutations.
    """
    results = []
    try:
        gray = cv2.cvtColor(img_color, cv2.COLOR_BGR2GRAY)
        gray_clean = remove_bars_lines(gray)
        data_clean = run_tesseract_image_to_data(gray_clean)
        data_orig = run_tesseract_image_to_data(gray)
        tokens = collect_tokens_from_data(data_clean) + [t for t in collect_tokens_from_data(data_orig)]
        # dedupe by x,y roughly
        seen = set(); uniq_tokens = []
        for t in tokens:
            k = (t['x'], t['y'], t['w'], t['h'], t['text'])
            if k in seen:
                continue
            seen.add(k)
            uniq_tokens.append(t)
        lines = group_tokens_lines(uniq_tokens, y_tol=12)
        for L in lines:
            toks = sorted(L['tokens'], key=lambda x: x['x'])
            # build merged contiguous blocks by small gaps (heuristic)
            merged_blocks = []
            cur = []
            for i, tk in enumerate(toks):
                if not cur:
                    cur = [tk]
                    continue
                prev = cur[-1]
                gap = tk['x'] - (prev['x'] + prev['w'])
                # allow gap up to some multiple of avg char width (use prev width as heuristic)
                avg_char = max(1, prev['w'] // max(1, len(prev['digits']) or 1))
                gap_tol = max(12, avg_char * 3)
                if gap <= gap_tol*5:  # be permissive for merging; block will be pruned by best window logic
                    cur.append(tk)
                else:
                    merged_blocks.append(cur)
                    cur = [tk]
            if cur:
                merged_blocks.append(cur)
            # for each merged block find best 15-digit window
            for block in merged_blocks:
                res = best_window_for_merged_block(block)
                if res:
                    imei, score = res
                    results.append((imei, score, 'tokens'))
    except Exception:
        pass
    return results

def extract_from_label_crop_strategy(img_color):
    results = []
    try:
        gray = cv2.cvtColor(img_color, cv2.COLOR_BGR2GRAY)
        data = run_tesseract_image_to_data(gray)
        tokens = collect_tokens_from_data(data)
        labels = [t for t in tokens if re.search(r'\bimei\b', t['text'], re.I) or re.search(r'imei1|imei2', t['text'], re.I)]
        for lab in labels:
            x1 = lab['x'] + lab['w']
            x2 = min(img_color.shape[1], x1 + 700)
            y1 = max(0, lab['y'] - 8)
            y2 = min(img_color.shape[0], lab['y'] + lab['h'] + 40)
            crop = img_color[y1:y2, x1:x2]
            if crop.size == 0:
                continue
            cgray = cv2.cvtColor(crop, cv2.COLOR_BGR2GRAY)
            cclean = remove_bars_lines(cgray)
            # try PSM 7 and PSM 6
            for cfg in (r'--oem 3 --psm 7 -c tessedit_char_whitelist=0123456789',
                        r'--oem 3 --psm 6 -c tessedit_char_whitelist=0123456789'):
                try:
                    text = pytesseract.image_to_string(cclean, config=cfg)
                except Exception:
                    text = ""
                for f in re.findall(r'\d{15}', text):
                    results.append((f, 300.0, 'label'))
    except Exception:
        pass
    return results

def extract_from_whole_image_strategy(img_color):
    results = []
    try:
        gray = cv2.cvtColor(img_color, cv2.COLOR_BGR2GRAY)
        gclean = remove_bars_lines(gray)
        for cfg in (r'--oem 3 --psm 11 -c tessedit_char_whitelist=0123456789',
                    r'--oem 3 --psm 6 -c tessedit_char_whitelist=0123456789'):
            try:
                text = pytesseract.image_to_string(gclean, config=cfg)
            except Exception:
                text = ""
            for f in re.findall(r'\d{15}', text):
                results.append((f, 20.0, 'whole'))
    except Exception:
        pass
    return results

def extract_from_barcode_strategy(img_color):
    results = []
    if not _PYZBAR_AVAILABLE:
        return results
    try:
        syms = pyzbar_decode(img_color)
        for s in syms:
            try:
                data = s.data.decode('utf-8', errors='ignore')
                for f in re.findall(r'\d{15}', data):
                    results.append((f, 500.0, 'barcode'))
            except Exception:
                pass
    except Exception:
        pass
    return results

# ========== Main strict extraction pipeline ==========
def extract_strict_imeis(image_basename):
    # image path is always IMAGE_DIR + basename
    image_path = os.path.join(IMAGE_DIR, image_basename)
    if not os.path.exists(image_path):
        return []
    try:
        img_color = cv2.imread(image_path)
        if img_color is None:
            return []
        img_color = limit_max_dim(img_color, max_dim=1600)

        # gather candidates from multiple independent strategies
        candidates = []  # list of tuples (imei, score, source)
        candidates += extract_from_barcode_strategy(img_color)
        candidates += extract_from_label_crop_strategy(img_color)
        candidates += extract_from_tokens_strategy(img_color)
        candidates += extract_from_whole_image_strategy(img_color)

        if not candidates:
            return []

        # aggregate sources and scores
        agg = {}  # imei -> {'score':best, 'sources':set(), 'count':int}
        for imei, score, source in candidates:
            if not re.fullmatch(r'\d{15}', imei):
                continue
            rec = agg.get(imei)
            if rec is None:
                agg[imei] = {'score': float(score), 'sources': {str(source)}, 'count': 1}
            else:
                rec['score'] = max(rec['score'], float(score))
                rec['sources'].add(str(source))
                rec['count'] += 1

        # strict filter: require Luhn valid AND (found by label or barcode OR count >= 2)
        final = []
        for imei, info in agg.items():
            if not luhn_ok(imei):
                continue
            if 'label' in info['sources'] or 'barcode' in info['sources'] or info['count'] >= 2:
                final.append((imei, info['score'], info['count'], info['sources']))

        # sort by count desc, then score desc
        final_sorted = sorted(final, key=lambda x: (-x[2], -x[1]))

        result = [item[0] for item in final_sorted]

        return result
    except Exception:
        return []

# ========== CLI ==========
def main():
    try:
        parser = argparse.ArgumentParser(add_help=False)
        parser.add_argument("--image", required=True)
        args, _ = parser.parse_known_args()
        basename = os.path.basename(args.image)
        imeis = extract_strict_imeis(basename)
        safe_print_json(imeis)
    except Exception:
        safe_print_json([])

if __name__ == "__main__":
    main()
