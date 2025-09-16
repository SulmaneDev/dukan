<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateGeneralVoucherRequest;
use App\Http\Requests\UpdateGeneralVoucherRequest;
use App\Models\GeneralVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralVoucherService
{
    // List all general vouchers for the current user
    public function index(Request $request)
    {
        $vouchers = $request->user()->generalVoucher()->get();

        return view('pages.admin.general_voucher.index', [
            'vouchers' => $vouchers,
        ]);
    }

    // Show the create form
    public function create(Request $request)
    {
        return view('pages.admin.general_voucher.create');
    }

    // Store a new general voucher
    public function store(CreateGeneralVoucherRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        GeneralVoucher::create($data);

        return redirect()->route('admin.general_voucher.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "General Voucher added successfully.",
        ]);
    }

    // Show edit form
    public function edit(Request $request, string $id)
    {
        $voucher = $request->user()->generalVoucher()->find($id);
        if (!$voucher) {
            return redirect()->route('admin.general_voucher.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "General Voucher not found.",
            ]);
        }

        return view('pages.admin.general_voucher.edit', compact('voucher'));
    }

    // Update an existing general voucher
    public function update(UpdateGeneralVoucherRequest $request, string $id)
    {
        $voucher = $request->user()->generalVoucher()->find($id);
        if (!$voucher) {
            return redirect()->route('admin.general_voucher.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "General Voucher not found.",
            ]);
        }

        $voucher->update($request->validated());

        return redirect()->route('admin.general_voucher.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "General Voucher updated successfully.",
        ]);
    }

    // Delete one or multiple general vouchers
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
                'message' => 'No vouchers selected for deletion.',
            ]);
        }

        $deletedCount = $request->user()->generalVoucher()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount General Voucher(s) deleted successfully.",
        ]);
    }
}
