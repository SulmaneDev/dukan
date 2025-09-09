<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateSaleReturnRequest;
use App\Http\Requests\UpdateSaleReturnRequest;
use App\Services\Admin\SaleReturnService;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    protected SaleReturnService $service;

    public function __construct(SaleReturnService $service)
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

    public function store(CreateSaleReturnRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(Request $request, string $id)
    {
        return $this->service->edit($request, $id);
    }

    public function update(UpdateSaleReturnRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy(DeletableRequest $request)
    {
        return $this->service->destroy($request);
    }
}
