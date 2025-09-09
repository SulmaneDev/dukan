<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerService
{
    public function index(Request $req)
    {
        $customers = $req->user()->customer()->get();

        return view('pages.admin.customer.index', [
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        return view('pages.admin.customer.create');
    }

    public function store(CreateCustomerRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cnic_front_image')) {
            $file = $request->file('cnic_front_image');
            $data['cnic_front_image'] = $file->store('customers/cnic_front', 'public');
        }

        if ($request->hasFile('cnic_back_image')) {
            $file = $request->file('cnic_back_image');
            $data['cnic_back_image'] = $file->store('customers/cnic_back', 'public');
        }

        $data['code'] = Str::upper(Str::random(6));
        $data['user_id'] = Auth::id();

        $customer = Customer::create($data);

        return redirect()->route('admin.customer.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Customer '{$customer->name}' created successfully.",
        ]);
    }

    public function edit(Request $req, string $id)
    {
        $customer = $req->user()->customer()->where('id', $id)->first();

        if (!$customer) {
            return redirect()->route('admin.customer.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Customer not found.",
            ]);
        }

        return view('pages.admin.customer.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, string $id)
    {
        $data = $request->validated();
        $customer = $request->user()->customer()->where('id', $id)->first();

        if (!$customer) {
            return redirect()->route('admin.customer.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Customer not found.",
            ]);
        }

        if ($request->hasFile('cnic_front_image')) {
            if ($customer->cnic_front_image && Storage::disk('public')->exists($customer->cnic_front_image)) {
                Storage::disk('public')->delete($customer->cnic_front_image);
            }
            $data['cnic_front_image'] = $request->file('cnic_front_image')->store('customers/cnic_front', 'public');
        } else {
            $data['cnic_front_image'] = $customer->cnic_front_image;
        }

        if ($request->hasFile('cnic_back_image')) {
            if ($customer->cnic_back_image && Storage::disk('public')->exists($customer->cnic_back_image)) {
                Storage::disk('public')->delete($customer->cnic_back_image);
            }
            $data['cnic_back_image'] = $request->file('cnic_back_image')->store('customers/cnic_back', 'public');
        } else {
            $data['cnic_back_image'] = $customer->cnic_back_image;
        }

        $customer->update($data);

        return redirect()->route('admin.customer.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Customer '{$customer->name}' updated successfully.",
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
                'message' => 'No customers selected for deletion.',
            ]);
        }

        $customers = $request->user()->customer()->whereIn('id', $ids)->get();

        foreach ($customers as $customer) {
            if ($customer->cnic_front_image && Storage::disk('public')->exists($customer->cnic_front_image)) {
                Storage::disk('public')->delete($customer->cnic_front_image);
            }
            if ($customer->cnic_back_image && Storage::disk('public')->exists($customer->cnic_back_image)) {
                Storage::disk('public')->delete($customer->cnic_back_image);
            }
        }

        $deletedCount = $request->user()->customer()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount customer(s) deleted successfully.",
        ]);
    }
}
