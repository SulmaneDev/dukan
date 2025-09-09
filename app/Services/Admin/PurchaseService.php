<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreatePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Supplier;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseService
{
    public function index(Request $req)
    {
        $purchases = $req->user()
            ->purchase()
            ->with(['product', 'brand'])
            ->get();
        return view('pages.admin.purchase.index', [
            'purchases' => $purchases,
        ]);
    }

    public function create()
    {
        $user = Auth::user()->load(['customer', 'supplier']);

        $suppliers = $user->supplier instanceof \Illuminate\Support\Collection
            ? $user->supplier
            : collect([$user->supplier])->filter();

        $customers = $user->customer instanceof \Illuminate\Support\Collection
            ? $user->customer
            : collect([$user->customer])->filter();

        return view('pages.admin.purchase.create', [
            'brands'   => $user->brand()->get(),
            'products' => $user->product()->get(),
            'parties'  => $suppliers->map(fn($s) => [
                'id'   => $s->id,
                'type' => 'supplier',
                'name' => $s->name,
            ])
                ->concat(
                    $customers->map(fn($c) => [
                        'id'   => $c->id,
                        'type' => 'customer',
                        'name' => $c->name,
                    ])
                ),
        ]);
    }


    public function store(CreatePurchaseRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // JSON encode imeis if array
        if (isset($data['imeis']) && is_array($data['imeis'])) {
            $data['imeis'] = json_encode($data['imeis']);
        }

        // Party handling
        $partyType = $request->input('party_type'); // "supplier" or "customer"
        $partyId = $request->input('party_id');

        if ($partyType === 'supplier') {
            $party = Auth::user()->supplier()->findOrFail($partyId);
            $purchase = $party->purchases()->create($data + ['party_type' => Supplier::class]);
        } elseif ($partyType === 'customer') {
            $party = Auth::user()->customer()->findOrFail($partyId);
            $purchase = $party->purchases()->create($data + ['party_type' => Customer::class]);
        } else {
            return redirect()->back()->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => 'Invalid party type.',
            ]);
        }

        return redirect()->route('admin.purchase.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Purchase #{$purchase->id} created successfully.",
        ]);
    }

    public function edit(Request $req, string $id)
    {
        $purchase = $req->user()->purchase()
            ->where('id', $id)
            ->with(['party', 'brand', 'product'])
            ->first();

        if (!$purchase) {
            return redirect()->route('admin.purchase.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Purchase not found.",
            ]);
        }

        $user = $req->user()->load(['supplier', 'customer']); 

        return view('pages.admin.purchase.edit', [
            'purchase' => $purchase,
            'brands'   => $req->user()->brand()->get(),
            'products' => $req->user()->product()->get(),
            'parties'  => $user->supplier
                ->map(fn($s) => ['id' => $s->id, 'type' => 'supplier', 'name' => $s->name])
                ->concat(
                    $user->customer->map(fn($c) => ['id' => $c->id, 'type' => 'customer', 'name' => $c->name])
                ),
        ]);
    }

    public function update(UpdatePurchaseRequest $request, string $id)
    {
        $data = $request->validated();
        $purchase = $request->user()->purchase()->where('id', $id)->first();

        if (!$purchase) {
            return redirect()->route('admin.purchase.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Purchase not found.",
            ]);
        }

        if (isset($data['imeis']) && is_array($data['imeis'])) {
            $data['imeis'] = json_encode($data['imeis']);
        }

        $partyType = $request->input('party_type');
        $partyId = $request->input('party_id');

        if ($partyType === 'supplier') {
            $purchase->update($data + ['party_type' => Supplier::class, 'party_id' => $partyId]);
        } elseif ($partyType === 'customer') {
            $purchase->update($data + ['party_type' => Customer::class, 'party_id' => $partyId]);
        }

        return redirect()->route('admin.purchase.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Purchase #{$purchase->id} updated successfully.",
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
                'message' => 'No purchases selected for deletion.',
            ]);
        }

        $deletedCount = $request->user()->purchase()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount purchase(s) deleted successfully.",
        ]);
    }
}
