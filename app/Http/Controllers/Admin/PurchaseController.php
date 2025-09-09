<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreatePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Services\Admin\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected PurchaseService $service;

    public function __construct(PurchaseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function create()
    {
        return $this->service->create();
    }

    public function store(CreatePurchaseRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(Request $request, string $id)
    {
        return $this->service->edit($request, $id);
    }

    public function update(UpdatePurchaseRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy(DeletableRequest $request)
    {
        return $this->service->destroy($request);
    }
}
