<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Services\Admin\ExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected ExpenseService $service;

    public function __construct(ExpenseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function create(Request $request)
    {
        return $this->service->create($request);
    }

    public function store(CreateExpenseRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(Request $request, string $id)
    {
        return $this->service->edit($request, $id);
    }

    public function update(UpdateExpenseRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy(DeletableRequest $request)
    {
        return $this->service->destroy($request);
    }
}
