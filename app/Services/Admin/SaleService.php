<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleService
{
    public function index(Request $req)
    {
        $sales = $req->user()
            ->sale()
            ->with(['product', 'customer'])
            ->get();

        return view('pages.admin.sale.index', [
            'sales' => $sales,
        ]);
    }

    public function create()
    {
        $user = Auth::user()->load(['customer']);

        $customers = $user->customer instanceof \Illuminate\Support\Collection
            ? $user->customer
            : collect([$user->customer])->filter();

        return view('pages.admin.sale.create', [
            'brands'   => $user->brand()->get(),
            'products' => $user->product()->get(),
            'purchase'=> $user->purchase()->with(['product','brand'])->get(),
            'customers' => $customers->map(fn($c) => [
                'id'   => $c->id,
                'name' => $c->name,
            ]),
        ]);
    }

    public function store(CreateSaleRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['reference_id'] = $digits = str_pad((string)rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        if (isset($data['imeis']) && is_array($data['imeis'])) {
            $data['imeis'] = json_encode($data['imeis']);
        }

        $customerId = $request->input('customer_id');
        $customer = Auth::user()->customer()->findOrFail($customerId);

        $sale = $customer->sales()->create($data);

        return redirect()->route('admin.sale.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Sale #{$sale->id} created successfully.",
        ]);
    }

    public function edit(Request $req, string $id)
    {
        $sale = $req->user()->sale()
            ->where('id', $id)
            ->with(['customer','product'])
            ->first();

        if (!$sale) {
            return redirect()->route('admin.sale.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Sale not found.",
            ]);
        }

        $user = $req->user()->load(['customer']);

        return view('pages.admin.sale.edit', [
            'sale'     => $sale,
            'brands'   => $req->user()->brand()->get(),
            'products' => $req->user()->product()->get(),
            'customers' => $user->customer->map(fn($c) => [
                'id'   => $c->id,
                'name' => $c->name,
            ]),
        ]);
    }

    public function update(UpdateSaleRequest $request, string $id)
    {
        $data = $request->validated();
        $sale = $request->user()->sale()->where('id', $id)->first();

        if (!$sale) {
            return redirect()->route('admin.sale.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Sale not found.",
            ]);
        }

        if (isset($data['imeis']) && is_array($data['imeis'])) {
            $data['imeis'] = json_encode($data['imeis']);
        }

        $sale->update($data);

        return redirect()->route('admin.sale.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Sale #{$sale->id} updated successfully.",
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
                'message' => 'No sales selected for deletion.',
            ]);
        }

        $deletedCount = $request->user()->sale()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount sale(s) deleted successfully.",
        ]);
    }
}
