<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateReceiptRequest;
use App\Http\Requests\UpdateReceiptRequest;
use App\Models\CustomerReceipt as Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptService
{
    // List all receipts for the current user
    public function index(Request $request)
    {
        $receipts = $request->user()->receipt()->with('customer')->get();

        return view('pages.admin.receipt.index', [
            'receipts' => $receipts,
        ]);
    }

    // Show the create form
    public function create(Request $request)
    {
        $customers = $request->user()->customer()->get();
        return view('pages.admin.receipt.create', [
            'customers' => $customers,
        ]);
    }

    // Store a new receipt
    public function store(CreateReceiptRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        Receipt::create($data);

        return redirect()->route('admin.receipt.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Receipt added successfully.",
        ]);
    }

    // Show edit form
    public function edit(Request $request, string $id)
    {
        $receipt = $request->user()->receipt()->find($id);
        if (!$receipt) {
            return redirect()->route('admin.receipt.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Receipt not found.",
            ]);
        }

        $customers = $request->user()->customer()->get();
        return view('pages.admin.receipt.edit', compact('receipt', 'customers'));
    }

    // Update an existing receipt
    public function update(UpdateReceiptRequest $request, string $id)
    {
        $receipt = $request->user()->receipt()->find($id);
        if (!$receipt) {
            return redirect()->route('admin.receipt.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Receipt not found.",
            ]);
        }

        $receipt->update($request->validated());

        return redirect()->route('admin.receipt.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Receipt updated successfully.",
        ]);
    }

    // Delete one or multiple receipts
    public function destroy(DeletableRequest $request)
    {
        $ids = $request->input('deletable_ids');
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => 'No receipts selected for deletion.',
            ]);
        }

        $deletedCount = $request->user()->receipt()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount receipt(s) deleted successfully.",
        ]);
    }
}
