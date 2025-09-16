<?php

namespace App\Http\Controllers;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateSupplierReceiptRequest;
use App\Http\Requests\UpdateSupplierReceiptRequest;
use App\Services\Admin\SupplierReceiptService;
use Illuminate\Http\Request;

class SupplierReceiptController extends Controller
{
    protected SupplierReceiptService $receiptService;

    public function __construct(SupplierReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    public function index(Request $request)
    {
        return $this->receiptService->index($request);
    }

    public function create(Request $request)
    {
        return $this->receiptService->create($request);
    }

    public function store(CreateSupplierReceiptRequest $request)
    {
        return $this->receiptService->store($request);
    }

    public function edit(Request $request, string $id)
    {
        return $this->receiptService->edit($request, $id);
    }

    public function update(UpdateSupplierReceiptRequest $request, string $id)
    {
        return $this->receiptService->update($request, $id);
    }

    public function destroy(DeletableRequest $request)
    {
        return $this->receiptService->destroy($request);
    }
}
