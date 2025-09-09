<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    function index(Request $request)
    {
        $relations = $request->user()->load(['product', 'brand']);
        return view('pages.admin.product.index', [
            'products' => $relations->product,
        ]);
    }
    function create(Request $request)
    {
        $brands = $request->user()->brand()->get();
        return view('pages.admin.product.create', [
            'brands' => $brands
        ]);
    }
    function store(CreateProductRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $data['image'] = $file->store('products', 'public');
        }
        $data['code'] = Str::random(6);
        $data['user_id'] = Auth::id();
        $product = new Product();
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->image = $data['image'];
        $product->code = $data['code'];
        $product->brand_id = $data['brand_id'];
        $product->user_id = $data['user_id'];
        $product->save();
        return redirect()->route('admin.product.index')->with('alert', [
            'type' => "success",
            "title" => "Success",
            "message" => "Product '{$product->name}' created successfully.",
        ]);
    }
    function edit(Request $req, string $id)
    {
        $product = $req->user()->product()->where('id', $id)->first();
        $brands = $req->user()->brand()->get();
        if (!$product) {
            return redirect()->route('admin.product.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Product not found.",
            ]);
        };
        return view('pages.admin.product.edit', compact('product', 'brands'));
    }
    function update(UpdateProductRequest $request, string $id)
    {
        $data = $request->validated();
        $product = $request->user()->product()->where('id', $id)->first();
        if (!$product) {
            return redirect()->route('admin.product.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Product not found.",
            ]);
        }
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $file = $request->file('image');
            $data['image'] = $file->store('products', 'public');
        } else {
            $data['image'] = $product->image;
        }

        $product->update([
            'name' => $data['name'],
            'price' => $data['price'],
            'brand_id' => $data['brand_id'],
            'image' => $data['image'],
        ]);

        return redirect()->route('admin.product.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Product '{$product->name}' updated successfully.",
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
                'message' => 'No products selected for deletion.',
            ]);
        }

        $products = $req->user()->product()->whereIn('id', $ids)->get();
        foreach ($products as $product) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        }

        $deletedCount = $req->user()->product()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount product(s) deleted successfully.",
        ]);
    }
}
