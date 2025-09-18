<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\PurchaseReturn;
use App\Models\SaleReturn;
use App\Models\CustomerReceipt;
use App\Models\SupplierReceipt;
use App\Models\Expense;
use App\Models\GeneralVoucher;

class NewLedgerService
{
    public function index(Request $request)
    {
        $start = $request->query('start_date') ? Carbon::parse($request->query('start_date'))->startOfDay() : null;
        $end = $request->query('end_date') ? Carbon::parse($request->query('end_date'))->endOfDay() : null;

        $type = $request->query('type');
        $party = $request->query('party');

        $rows = new Collection();

        // Fetch all transactions
        $purchases = Purchase::with(['product', 'brand', 'supplier'])->get();
        $sales = Sale::with(['product', 'brand', 'customer'])->get();
        $purchaseReturns = PurchaseReturn::with(['product', 'brand', 'party'])->get();
        $saleReturns = SaleReturn::with(['product', 'brand', 'customer'])->get();
        $customerReceipts = CustomerReceipt::with('customer')->get();
        $supplierReceipts = SupplierReceipt::with('supplier')->get();
        $expenses = Expense::with('category')->get();
        $generalVouchers = GeneralVoucher::all();

        // Process transactions
        foreach ($purchases as $p) {
            $rows->push($this->formatPurchase($p));
        }
        foreach ($sales as $s) {
            $rows->push($this->formatSale($s));
        }
        foreach ($purchaseReturns as $pr) {
            $rows->push($this->formatPurchaseReturn($pr));
        }
        foreach ($saleReturns as $sr) {
            $rows->push($this->formatSaleReturn($sr));
        }
        foreach ($customerReceipts as $cr) {
            $rows->push($this->formatCustomerReceipt($cr));
        }
        foreach ($supplierReceipts as $sr) {
            $rows->push($this->formatSupplierReceipt($sr));
        }
        foreach ($expenses as $e) {
            $rows->push($this->formatExpense($e));
        }
        foreach ($generalVouchers as $gv) {
            $rows->push($this->formatGeneralVoucher($gv));
        }

        // Filter rows
        if ($start && $end) {
            $rows = $rows->whereBetween('date', [$start, $end]);
        }
        if ($type) {
            $rows = $rows->where('type', $type);
        }
        if ($party) {
            $rows = $rows->filter(function ($row) use ($party) {
                return str_contains(strtolower($row->party), strtolower($party));
            });
        }

        $rows = $rows->sortByDesc('date')->values();

        // Calculate summary
        $summary = $this->calculateSummary($rows);

        return view('pages.admin.ledger.new_index', [
            'rows' => $rows,
            'summary' => $summary,
            'start' => $start,
            'end' => $end,
            'filters' => [
                'type' => $type,
                'party' => $party,
            ],
        ]);
    }

    private function formatPurchase($p)
    {
        $qty = $this->countImeis($p->imeis);
        $gross = floatval($p->price ?? 0) * $qty;
        $discount = $this->purchaseDiscountAmount($p, $qty);
        $net = $gross - $discount;
        return (object)[
            'date' => $p->created_at,
            'type' => 'Purchase',
            'description' => "Purchased {" . ($p->product->name ?? 'N/A') . "}",
            'debit' => $net,
            'credit' => 0,
            'party' => $p->supplier->name ?? 'N/A',
        ];
    }

    private function formatSale($s)
    {
        $qty = $this->countImeis($s->imeis);
        $gross = floatval($s->price ?? 0) * $qty;
        $discount = $this->saleDiscountAmount($s, $qty);
        $net = $gross - $discount;
        return (object)[
            'date' => $s->created_at,
            'type' => 'Sale',
            'description' => "Sold {" . ($s->product->name ?? 'N/A') . "}",
            'debit' => 0,
            'credit' => $net,
            'party' => $s->customer->name ?? 'N/A',
        ];
    }

    private function formatPurchaseReturn($pr)
    {
        $qty = $this->countImeis($pr->imeis);
        $gross = floatval($pr->price ?? 0) * $qty;
        $discount = floatval($pr->fixed_discount ?? 0);
        $net = $gross - $discount;
        return (object)[
            'date' => $pr->created_at,
            'type' => 'Purchase Return',
            'description' => "Returned {" . ($pr->product->name ?? 'N/A') . "}",
            'debit' => 0,
            'credit' => $net,
            'party' => $pr->party->name ?? 'N/A',
        ];
    }

    private function formatSaleReturn($sr)
    {
        $qty = $this->countImeis($sr->imeis);
        $gross = floatval($sr->price ?? 0) * $qty;
        $discount = floatval($sr->fixed_discount ?? 0);
        $net = $gross - $discount;
        return (object)[
            'date' => $sr->created_at,
            'type' => 'Sale Return',
            'description' => "Returned {" . ($sr->product->name ?? 'N/A') . "}",
            'debit' => $net,
            'credit' => 0,
            'party' => $sr->customer->name ?? 'N/A',
        ];
    }

    private function formatCustomerReceipt($cr)
    {
        return (object)[
            'date' => $cr->date,
            'type' => 'Customer Receipt',
            'description' => $cr->description,
            'debit' => $cr->amount,
            'credit' => 0,
            'party' => $cr->customer->name ?? 'N/A',
        ];
    }

    private function formatSupplierReceipt($sr)
    {
        return (object)[
            'date' => $sr->date,
            'type' => 'Supplier Receipt',
            'description' => $sr->description,
            'debit' => 0,
            'credit' => $sr->amount,
            'party' => $sr->supplier->name ?? 'N/A',
        ];
    }

    private function formatExpense($e)
    {
        return (object)[
            'date' => $e->date,
            'type' => 'Expense',
            'description' => $e->description,
            'debit' => $e->amount,
            'credit' => 0,
            'party' => $e->category->name ?? 'N/A',
        ];
    }

    private function formatGeneralVoucher($gv)
    {
        return (object)[
            'date' => $gv->date,
            'type' => 'General Voucher',
            'description' => $gv->description,
            'debit' => 0,
            'credit' => 0,
            'party' => '',
        ];
    }

    private function countImeis($imeis)
    {
        if (is_array($imeis)) return count($imeis);
        if (is_null($imeis) || $imeis === '') return 0;
        $decoded = @json_decode($imeis, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return count($decoded);
        }
        return strlen(trim($imeis)) ? count(array_filter(array_map('trim', explode(',', $imeis)))) : 0;
    }

    private function purchaseDiscountAmount($purchase, $qty)
    {
        $fixed = floatval($purchase->fixed_discount ?? 0);
        $coupon = floatval($purchase->coupon_discount ?? 0);
        $percent = floatval($purchase->percent_discount ?? 0);
        $gross = floatval($purchase->price ?? 0) * $qty;
        $percentAmount = ($percent > 0) ? ($gross * ($percent / 100)) : 0;
        return $fixed + $coupon + $percentAmount;
    }

    private function saleDiscountAmount($sale, $qty)
    {
        $fixed = floatval($sale->fixed_discount ?? 0);
        return $fixed;
    }

    private function calculateSummary($rows)
    {
        $totalDebit = $rows->sum('debit');
        $totalCredit = $rows->sum('credit');
        $balance = $totalDebit - $totalCredit;

        return [
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'balance' => $balance,
            'total_transactions' => $rows->count(),
        ];
    }
}