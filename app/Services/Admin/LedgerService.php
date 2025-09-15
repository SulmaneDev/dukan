<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LedgerService
{
    public function index(Request $request)
    {
        $start = $request->query('start_date') ? Carbon::parse($request->query('start_date'))->startOfDay() : null;
        $end   = $request->query('end_date') ? Carbon::parse($request->query('end_date'))->endOfDay() : null;

        $productId = $request->query('product_id');
        $brandId   = $request->query('brand_id');
        $party     = $request->query('party');
        $type      = $request->query('type');

        // Build queries
        $purchasesQuery = $request->user()->purchase()->with(['product', 'brand', 'party', 'supplier']);
        $salesQuery     = $request->user()->sale()->with(['product', 'brand', 'customer']);
        $purchaseReturnQuery = $request->user()->purchaseReturn()->with(['product', 'brand', 'party']);
        $saleReturnQuery = $request->user()->saleReturn()->with(['product', 'brand', 'customer']);

        // Filters closure
        $applyFilters = function ($q) use ($start, $end, $productId, $brandId, $party) {
            if ($start && $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }
            if ($productId) {
                $q->where('product_id', $productId);
            }
            if ($brandId) {
                $q->where('brand_id', $brandId);
            }
            if ($party) {
                $q->where(function ($sub) use ($party) {
                    $sub->whereHas('supplier', fn($r) => $r->where('name', 'like', "%{$party}%"))
                        ->orWhereHas('customer', fn($r) => $r->where('name', 'like', "%{$party}%"))
                        ->orWhereHas('party', fn($r) => $r->where('name', 'like', "%{$party}%"));
                });
            }
        };

        $applyFilters($purchasesQuery);
        $applyFilters($salesQuery);
        $applyFilters($purchaseReturnQuery);
        $applyFilters($saleReturnQuery);

        $purchases = $purchasesQuery->get();
        $sales = $salesQuery->get();
        $purchaseReturns = $purchaseReturnQuery->get();
        $saleReturns = $saleReturnQuery->get();

        $rows = new Collection();

        // helpers
        $countImeis = function ($imeis) {
            if (is_array($imeis)) return count($imeis);
            if (is_null($imeis) || $imeis === '') return 0;
            $decoded = @json_decode($imeis, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return count($decoded);
            }
            return strlen(trim($imeis)) ? count(array_filter(array_map('trim', explode(',', $imeis)))) : 0;
        };

        $purchaseDiscountAmount = function ($purchase, $qty) {
            $fixed = floatval($purchase->fixed_discount ?? 0);
            $coupon = floatval($purchase->coupon_discount ?? 0);
            $percent = floatval($purchase->percent_discount ?? 0);
            $gross = floatval($purchase->price ?? 0) * $qty;
            $percentAmount = ($percent > 0) ? ($gross * ($percent / 100)) : 0;
            return $fixed + $coupon + $percentAmount;
        };

        $saleDiscountAmount = function ($sale, $qty) {
            $fixed = floatval($sale->fixed_discount ?? 0);
            // if sales later have percent, add here
            return $fixed;
        };

        $formatFrom = function ($record, $explicitRelation = null) {
            if ($explicitRelation && isset($record->{$explicitRelation}) && $record->{$explicitRelation}) {
                $name = $record->{$explicitRelation}->name ?? null;
                if ($name) return ucfirst($explicitRelation) . ' — ' . $name;
            }
            if (isset($record->supplier) && $record->supplier) {
                return 'Supplier — ' . ($record->supplier->name ?? '-');
            }
            if (isset($record->customer) && $record->customer) {
                return 'Customer — ' . ($record->customer->name ?? '-');
            }
            if (isset($record->party) && $record->party) {
                $party = $record->party;
                $short = class_basename(get_class($party));
                $label = $short ?: 'Party';
                return "{$label} — " . ($party->name ?? '-');
            }
            if (isset($record->party_type) && isset($record->party_id)) {
                $type = $record->party_type ? class_basename($record->party_type) : 'Party';
                $name = $record->party->name ?? ($record->supplier->name ?? ($record->customer->name ?? '-'));
                return "{$type} — " . ($name ?? '-');
            }
            return 'N/A';
        };

        // Monetary calculations per record and push unified row objects
        $purchaseTotalAmount = 0.0;
        foreach ($purchases as $p) {
            $qty = $countImeis($p->imeis);
            $gross = floatval($p->price ?? 0) * $qty;
            $discount = $purchaseDiscountAmount($p, $qty);
            $orderTax = floatval($p->order_tax ?? 0); // interpreted as numeric amount
            $net = $gross - $discount + $orderTax;

            $rows->push((object)[
                'type' => 'Purchase',
                'quantity' => $qty,
                'total_discount' => $discount,
                'product' => $p->product->name ?? '-',
                'brand' => $p->brand->name ?? '-',
                'from' => $formatFrom($p, 'supplier'),
                'balance' => $this->resolveSupplierBalance($p->supplier ?? $p->party ?? null),
                'date' => optional($p->created_at)->format('Y-m-d H:i:s'),
                'gross' => $gross,
                'net' => $net,
            ]);

            $purchaseTotalAmount += $net;
        }

        $salesTotalAmount = 0.0;
        foreach ($sales as $s) {
            $qty = $countImeis($s->imeis);
            $gross = floatval($s->price ?? 0) * ($qty > 0 ? $qty : (intval($s->qty ?? 0) > 0 ? intval($s->qty) : 1));
            $discount = $saleDiscountAmount($s, $qty);
            $net = $gross - $discount;

            $rows->push((object)[
                'type' => 'Sale',
                'quantity' => $qty,
                'total_discount' => $discount,
                'product' => $s->product->name ?? '-',
                'brand' => $s->brand->name ?? '-',
                'from' => $formatFrom($s, 'customer'),
                'balance' => 'N/A',
                'date' => optional($s->created_at)->format('Y-m-d H:i:s'),
                'gross' => $gross,
                'net' => $net,
            ]);

            $salesTotalAmount += $net;
        }

        $purchaseReturnsTotal = 0.0;
        foreach ($purchaseReturns as $pr) {
            $qty = $countImeis($pr->imeis);
            $gross = floatval($pr->price ?? 0) * $qty;
            $discount = floatval($pr->fixed_discount ?? 0); // from your model
            $net = $gross - $discount; // net refund (reduces purchases)

            $rows->push((object)[
                'type' => 'Purchase Return',
                'quantity' => $qty,
                'total_discount' => $discount,
                'product' => $pr->product->name ?? '-',
                'brand' => $pr->brand->name ?? '-',
                'from' => $formatFrom($pr, 'party'),
                'balance' => $this->resolveSupplierBalance($pr->party ?? null),
                'date' => optional($pr->created_at)->format('Y-m-d H:i:s'),
                'gross' => $gross,
                'net' => $net,
            ]);

            $purchaseReturnsTotal += $net;
        }

        $saleReturnsTotal = 0.0;
        foreach ($saleReturns as $sr) {
            $qty = $countImeis($sr->imeis);
            $gross = floatval($sr->price ?? 0) * $qty;
            $discount = floatval($sr->fixed_discount ?? 0);
            $net = $gross - $discount; // refund amount reducing sales

            $rows->push((object)[
                'type' => 'Sale Return',
                'quantity' => $qty,
                'total_discount' => $discount,
                'product' => $sr->product->name ?? '-',
                'brand' => $sr->brand->name ?? '-',
                'from' => $formatFrom($sr, 'customer'),
                'balance' => 'N/A',
                'date' => optional($sr->created_at)->format('Y-m-d H:i:s'),
                'gross' => $gross,
                'net' => $net,
            ]);

            $saleReturnsTotal += $net;
        }

        // Net totals after subtracting returns
        $netPurchases = $purchaseTotalAmount - $purchaseReturnsTotal;
        $netSales = $salesTotalAmount - $saleReturnsTotal;

        $profit = $netSales - $netPurchases;
        $total_profit_amount = $profit > 0 ? $profit : 0;
        $total_loss_amount = $profit < 0 ? abs($profit) : 0;

        // total debt: sum latest supplier balances (numeric only)
        $total_debt = 0.0;
        $suppliers = $request->user()->supplier()->with('balance')->get();
        foreach ($suppliers as $supplier) {
            $val = $this->numericSupplierBalance($supplier);
            $total_debt += $val;
        }

        // optional type filter on unified rows
        if ($type) {
            $allowed = [
                'purchase' => 'Purchase',
                'sale' => 'Sale',
                'purchase_return' => 'Purchase Return',
                'sale_return' => 'Sale Return',
            ];
            if (isset($allowed[$type])) {
                $rows = $rows->filter(fn($r) => $r->type === $allowed[$type]);
            }
        }

        $rows = $rows->sortByDesc('date')->values();

        // summary for top UI
        $summary = [
            'rows_count' => $rows->count(),
            'total_quantity' => $rows->sum('quantity'),
            'total_discount' => $rows->sum('total_discount'),
            'total_purchase_amount' => $purchaseTotalAmount,
            'total_purchase_returns_amount' => $purchaseReturnsTotal,
            'net_purchases' => $netPurchases,
            'total_sale_amount' => $salesTotalAmount,
            'total_sale_returns_amount' => $saleReturnsTotal,
            'net_sales' => $netSales,
            'total_profit' => $total_profit_amount,
            'total_loss' => $total_loss_amount,
            'total_debt' => $total_debt,
        ];

        return view('pages.admin.ledger.index', [
            'rows' => $rows,
            'summary' => $summary,
            'start' => $start,
            'end' => $end,
            'filters' => [
                'product_id' => $productId,
                'brand_id' => $brandId,
                'party' => $party,
                'type' => $type,
            ],
        ]);
    }

    protected function resolveSupplierBalance($supplier)
    {
        if (! $supplier) return 'N/A';

        if (isset($supplier->balance) && $supplier->balance instanceof \Illuminate\Support\Collection) {
            $last = $supplier->balance->sortByDesc(fn($b) => $b->id ?? 0)->first();
            return $last->amount ?? 'N/A';
        }

        if (method_exists($supplier, 'balance')) {
            $b = $supplier->balance()->orderByDesc('id')->first();
            return $b->amount ?? 'N/A';
        }

        return 'N/A';
    }

    protected function numericSupplierBalance($supplier)
    {
        $val = $this->resolveSupplierBalance($supplier);
        if (is_numeric($val)) return floatval($val);
        return 0.0;
    }
}
