<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Services\Admin\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    protected SaleService $service;

    public function __construct(SaleService $service)
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

    public function store(CreateSaleRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(Request $request, string $id)
    {
        return $this->service->edit($request, $id);
    }

    public function update(UpdateSaleRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy(DeletableRequest $request)
    {
        return $this->service->destroy($request);
    }
}
