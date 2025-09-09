<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreatePurchaseReturnRequest;
use App\Http\Requests\UpdatePurchaseReturnRequest;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseReturnService
{
    public function index(Request $req)
    {
        $returns = $req->user()
            ->purchaseReturn()
            ->with(['product', 'brand', 'purchase'])
            ->get();

        return view('pages.admin.purchase_return.index', [
            'returns' => $returns,
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

        return view('pages.admin.purchase_return.create', [
            'brands'   => $user->brand()->get(),
            'products' => $user->product()->get(),
            'purchases'=> $user->purchase()->get(),
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

    public function store(CreatePurchaseReturnRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if (isset($data['imeis']) && is_array($data['imeis'])) {
            $data['imeis'] = json_encode($data['imeis']);
        }

        $partyType = $request->input('party_type');
        $partyId   = $request->input('party_id');

        if ($partyType === 'supplier') {
            $party = Auth::user()->supplier()->findOrFail($partyId);
            $return = $party->purchaseReturn()->create($data + ['party_type' => Supplier::class]);
        } elseif ($partyType === 'customer') {
            $party = Auth::user()->customer()->findOrFail($partyId);
            $return = $party->purchaseReturn()->create($data + ['party_type' => Customer::class]);
        } else {
            return redirect()->back()->with('alert', [
                'type'    => 'warning',
                'title'   => 'Warning',
                'message' => 'Invalid party type.',
            ]);
        }

        return redirect()->route('admin.purchase_return.index')->with('alert', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => "Purchase return #{$return->id} created successfully.",
        ]);
    }

    public function edit(Request $req, string $id)
    {
        $return = $req->user()->purchaseReturn()
            ->where('id', $id)
            ->with(['party', 'brand', 'product', 'purchase'])
            ->first();

        if (!$return) {
            return redirect()->route('admin.purchase_return.index')->with('alert', [
                'type'    => 'warning',
                'title'   => 'Warning',
                'message' => "Purchase return not found.",
            ]);
        }

        $user = $req->user()->load(['supplier', 'customer']);

        return view('pages.admin.purchase_return.edit', [
            'return'   => $return,
            'brands'   => $req->user()->brand()->get(),
            'products' => $req->user()->product()->get(),
            'purchases'=> $req->user()->purchase()->get(),
            'parties'  => $user->supplier
                ->map(fn($s) => ['id' => $s->id, 'type' => 'supplier', 'name' => $s->name])
                ->concat(
                    $user->customer->map(fn($c) => ['id' => $c->id, 'type' => 'customer', 'name' => $c->name])
                ),
        ]);
    }

    public function update(UpdatePurchaseReturnRequest $request, string $id)
    {
        $data = $request->validated();
        $return = $request->user()->purchaseReturn()->where('id', $id)->first();

        if (!$return) {
            return redirect()->route('admin.purchase_return.index')->with('alert', [
                'type'    => 'warning',
                'title'   => 'Warning',
                'message' => "Purchase return not found.",
            ]);
        }

        if (isset($data['imeis']) && is_array($data['imeis'])) {
            $data['imeis'] = json_encode($data['imeis']);
        }

        $partyType = $request->input('party_type');
        $partyId   = $request->input('party_id');

        if ($partyType === 'supplier') {
            $return->update($data + ['party_type' => Supplier::class, 'party_id' => $partyId]);
        } elseif ($partyType === 'customer') {
            $return->update($data + ['party_type' => Customer::class, 'party_id' => $partyId]);
        }

        return redirect()->route('admin.purchase_return.index')->with('alert', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => "Purchase return #{$return->id} updated successfully.",
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
                'message' => 'No purchase returns selected for deletion.',
            ]);
        }

        $deletedCount = $request->user()->purchaseReturn()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type'    => 'success',
            'title'   => 'Deleted',
            'message' => "$deletedCount purchase return(s) deleted successfully.",
        ]);
    }
}
