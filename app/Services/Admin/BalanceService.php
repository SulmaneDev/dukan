<?php

namespace App\Services\Admin;

use App\Http\Requests\BalanceRequest;
use App\Http\Requests\Common\DeletableRequest;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalanceService
{
    function index(Request $req)
    {
        $balances = $req->user()->balance()->with(['product', 'supplier'])->get();
        return view("pages.admin.balance.index", [
            'balances' => $balances,
        ]);
    }
    function edit(Request $req, string $id)
    {
        $balance = Balance::where('id', $id)->where('status', true)->where('user_id', Auth::id())->first();
        if (!$balance) {
            return redirect()->back()->with('alert', [
                'type' => "warning",
                "message" => "Balance " . $id . " not found.",
            ]);
        }

        return view("pages.admin.balance.edit", [
            'balance'   => $balance,
            'suppliers' => \App\Models\Supplier::all(),
            'products'  => \App\Models\Product::all(),
        ]);
    }



    function update(BalanceRequest $req, string $id)
    {
        $balance = $req->user()->balance()->find($id);

        if (!$balance) {
            return redirect()->route('admin.balance.index')->with('alert', [
                'type' => "warning",
                "message" => "Balance " . $id . " not found.",
            ]);
        }
        $data = $req->validated();
        $balance->update([
            'status' => false,
        ]);
        $newBalance = $req->user()->balance()->create([
            'supplier_id' => $balance->supplier_id,
            'product_id' => $balance->product_id,
            'balance' => $balance->balance - $data['balance'],
            'status' => true,
        ]);
        return redirect()->back()->with('toast', [
            'title' => 'Completed',
            'description' => "Balance updated successfully.",
            'variant' => 'success',
        ]);
    }

    function destroy(DeletableRequest $req)
    {

        $ids = $req->input('deletable_ids');
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => 'No sales selected for deletion.',
            ]);
        }

        $deletedCount = $req->user()->balance()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount sale(s) deleted successfully.",
        ]);
    }
}
