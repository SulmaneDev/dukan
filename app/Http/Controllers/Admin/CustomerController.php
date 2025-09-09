<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Services\Admin\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected CustomerService $service;

    function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    function index(Request $request)
    {
        return $this->service->index($request);
    }

    function create()
    {
        return $this->service->create();
    }

    function store(CreateCustomerRequest $request)
    {
        return $this->service->store($request);
    }

    function edit(Request $request, string $id)
    {
        return $this->service->edit($request, $id);
    }

    function update(UpdateCustomerRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    function destroy(DeletableRequest $request)
    {
        return $this->service->destroy($request);
    }
}
