<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupplierService
{
    public function index(Request $req)
    {
        $suppliers = $req->user()->supplier()->get();
        return view('pages.admin.supplier.index', [
            'suppliers' => $suppliers,
        ]);
    }
    public function create()
    {
        return view('pages.admin.supplier.create');
    }
    public function store(CreateSupplierRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $data['image'] = $file->store('suppliers', 'public');
        }

        $data['code'] = Str::random(8);
        $data['user_id'] = Auth::id();

        $supplier = Supplier::create($data);

        return redirect()->route('admin.supplier.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Supplier '{$supplier->name}' created successfully.",
        ]);
    }
    public function edit(Request $req, string $id)
    {
        $supplier = $req->user()->supplier()->where('id', $id)->first();

        if (!$supplier) {
            return redirect()->route('admin.supplier.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Supplier not found.",
            ]);
        }

        return view('pages.admin.supplier.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, string $id)
    {
        $data = $request->validated();
        $supplier = $request->user()->supplier()->where('id', $id)->first();

        if (!$supplier) {
            return redirect()->route('admin.supplier.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Supplier not found.",
            ]);
        }

        if ($request->hasFile('image')) {
            if ($supplier->image && Storage::disk('public')->exists($supplier->image)) {
                Storage::disk('public')->delete($supplier->image);
            }
            $file = $request->file('image');
            $data['image'] = $file->store('suppliers', 'public');
        } else {
            $data['image'] = $supplier->image;
        }

        $supplier->update($data);

        return redirect()->route('admin.supplier.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Supplier '{$supplier->name}' updated successfully.",
        ]);
    }

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
                'message' => 'No suppliers selected for deletion.',
            ]);
        }

        $suppliers = $request->user()->supplier()->whereIn('id', $ids)->get();

        foreach ($suppliers as $supplier) {
            if ($supplier->image && Storage::disk('public')->exists($supplier->image)) {
                Storage::disk('public')->delete($supplier->image);
            }
        }

        $deletedCount = $request->user()->supplier()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount supplier(s) deleted successfully.",
        ]);
    }
}
