<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $service;
    function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    function index(Request $request)
    {
        return $this->service->index($request);
    }
    function create(Request $request)
    {
        return $this->service->create($request);
    }
    function store(CreateProductRequest $request)
    {
        return $this->service->store($request);
    }
    function edit(Request $request, string $id)
    {
        return $this->service->edit($request, $id);
    }
    function update(UpdateProductRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }
    function destroy(DeletableRequest $rq)
    {
        return $this->service->destroy($rq);
    }
}
