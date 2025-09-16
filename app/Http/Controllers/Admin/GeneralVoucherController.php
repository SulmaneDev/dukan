<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateGeneralVoucherRequest;
use App\Http\Requests\UpdateGeneralVoucherRequest;
use App\Services\Admin\GeneralVoucherService;
use Illuminate\Http\Request;

class GeneralVoucherController extends Controller
{
    protected GeneralVoucherService $voucherService;

    public function __construct(GeneralVoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    public function index(Request $request)
    {
        return $this->voucherService->index($request);
    }

    public function create(Request $request)
    {
        return $this->voucherService->create($request);
    }

    public function store(CreateGeneralVoucherRequest $request)
    {
        return $this->voucherService->store($request);
    }

    public function edit(Request $request, string $id)
    {
        return $this->voucherService->edit($request, $id);
    }

    public function update(UpdateGeneralVoucherRequest $request, string $id)
    {
        return $this->voucherService->update($request, $id);
    }

    public function destroy(DeletableRequest $request)
    {
        return $this->voucherService->destroy($request);
    }
}
