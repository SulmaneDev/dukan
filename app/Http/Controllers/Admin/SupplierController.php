<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Services\Admin\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected SupplierService $service;
    function __construct(SupplierService $service)
    {
        return $this->service = $service;
    }

    function index(Request $request)
    {
        return $this->service->index($request);
    }
    function create()
    {
        return $this->service->create();
    }
    function store(CreateSupplierRequest $request)
    {
        return $this->service->store($request);
    }
    function edit(Request $request, string $id)
    {
        return $this->service->edit($request, $id);
    }
    function update(UpdateSupplierRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }
    function destroy(DeletableRequest $rq)
    {
        return $this->service->destroy($rq);
    }
}
