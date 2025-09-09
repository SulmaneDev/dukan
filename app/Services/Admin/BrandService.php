<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandService
{
    function index(Request $req)
    {
        $brands = $req->user()->brand()->get();
        return view('pages.admin.brand.index', [
            'brands' => $brands,
        ]);
    }
    function create()
    {
        return view('pages.admin.brand.create');
    }
    function store(CreateBrandRequest $req)
    {
        $data = $req->validated();
        $brand = new Brand();
        $brand->name = $data['name'];
        $brand->user_id = Auth::id();
        $brand->save();
        return redirect()->route('admin.brand.index')->with('alert', [
            'type' => 'success',
            'title' => "Success",
            "message" => "Brand $brand->name created successfully.",
        ]);
    }
    public function edit(Request $req, string $id)
    {
        $brand = $req->user()->brand()->find($id);
        if (!$brand) {
            return redirect()->route('admin.brand.index')->with('alert', [
                'type' => 'warning',
                'title' => "Warning",
                "message" => "Brand not found.",
            ]);
        };
        return view('pages.admin.brand.edit', compact("brand"));
    }
    function update(UpdateBrandRequest $req, string $id)
    {
        $data = $req->validated();
        $brand = $req->user()->brand()->findOrFail($id);
        if (isset($data['name'])) {
            $brand->name = $data['name'];
        }
        $brand->save();
        return redirect()->route('admin.brand.index')->with('alert', [
            'type' => 'success',
            'title' => "Success",
            'message' => "Brand '{$brand->name}' updated successfully.",
        ]);
    }
    public function destroy(DeletableRequest $req)
    {
        $ids = $req->input('deletable_ids');
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => 'No brands selected for deletion.',
            ]);
        }

        $deletedCount = $req->user()->brand()->whereIn('id', $ids)->delete();


        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount brand(s) deleted successfully.",
        ]);
    }
}
