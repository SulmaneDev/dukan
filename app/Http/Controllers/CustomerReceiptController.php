<?php

namespace App\Http\Controllers;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateReceiptRequest;
use App\Http\Requests\UpdateReceiptRequest;
use App\Services\Admin\ReceiptService;
use Illuminate\Http\Request;

class CustomerReceiptController extends Controller
{
    protected ReceiptService $receiptService;

    public function __construct(ReceiptService $receiptService)
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

    public function store(CreateReceiptRequest $request)
    {
        return $this->receiptService->store($request);
    }

    public function edit(Request $request, string $id)
    {
        return $this->receiptService->edit($request, $id);
    }

    public function update(UpdateReceiptRequest $request, string $id)
    {
        return $this->receiptService->update($request, $id);
    }

    public function destroy(DeletableRequest $request)
    {
        return $this->receiptService->destroy($request);
    }
}
