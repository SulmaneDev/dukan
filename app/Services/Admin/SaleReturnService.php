<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateSaleReturnRequest;
use App\Http\Requests\UpdateSaleReturnRequest;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleReturnService
{
    public function index(Request $req)
    {
        $returns = $req->user()
            ->saleReturn()
            ->with(['product', 'brand', 'sale', 'customer'])
            ->get();

        return view('pages.admin.sale_return.index', [
            'returns' => $returns,
        ]);
    }

    public function create()
    {
        $user = Auth::user()->load(['customer']);

        $customers = $user->customer instanceof \Illuminate\Support\Collection
            ? $user->customer
            : collect([$user->customer])->filter();

        return view('pages.admin.sale_return.create', [
            'brands'    => $user->brand()->get(),
            'products'  => $user->product()->get(),
            'sales'     => $user->sale()->get(),
            'customers' => $customers->map(fn($c) => [
                'id'   => $c->id,
                'name' => $c->name,
            ]),
        ]);
    }

    public function store(CreateSaleReturnRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['reference_id'] = str_pad((string)rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        if (isset($data['imeis']) && is_array($data['imeis'])) {
            $data['imeis'] = json_encode($data['imeis']);
        }

        $customerId = $request->input('customer_id');
        $customer = Auth::user()->customer()->findOrFail($customerId);

        $return = $customer->saleReturn()->create($data);

        return redirect()->route('admin.sale_return.index')->with('alert', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => "Sale return #{$return->id} created successfully.",
        ]);
    }

    public function edit(Request $req, string $id)
    {
        $return = $req->user()->saleReturn()
            ->where('id', $id)
            ->with(['customer','product','brand','sale'])
            ->first();

        if (!$return) {
            return redirect()->route('admin.sale_return.index')->with('alert', [
                'type'    => 'warning',
                'title'   => 'Warning',
                'message' => "Sale return not found.",
            ]);
        }

        $user = $req->user()->load(['customer']);

        return view('pages.admin.sale_return.edit', [
            'return'    => $return,
            'brands'    => $req->user()->brand()->get(),
            'products'  => $req->user()->product()->get(),
            'sales'     => $req->user()->sale()->get(),
            'customers' => $user->customer->map(fn($c) => [
                'id'   => $c->id,
                'name' => $c->name,
            ]),
        ]);
    }

    public function update(UpdateSaleReturnRequest $request, string $id)
    {
        $data = $request->validated();
        $return = $request->user()->saleReturn()->where('id', $id)->first();

        if (!$return) {
            return redirect()->route('admin.sale_return.index')->with('alert', [
                'type'    => 'warning',
                'title'   => 'Warning',
                'message' => "Sale return not found.",
            ]);
        }

        if (isset($data['imeis']) && is_array($data['imeis'])) {
            $data['imeis'] = json_encode($data['imeis']);
        }

        $return->update($data);

        return redirect()->route('admin.sale_return.index')->with('alert', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => "Sale return #{$return->id} updated successfully.",
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
                'type'    => 'warning',
                'title'   => 'Warning',
                'message' => 'No sale returns selected for deletion.',
            ]);
        }

        $deletedCount = $request->user()->saleReturn()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type'    => 'success',
            'title'   => 'Deleted',
            'message' => "$deletedCount sale return(s) deleted successfully.",
        ]);
    }
}
