<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;
use App\Http\Requests\Common\DeletableRequest;
use App\Services\Admin\ExpenseCategoryService;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    protected ExpenseCategoryService $service;

    public function __construct(ExpenseCategoryService $service)
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

    public function store(CreateExpenseCategoryRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(Request $request, string $id)
    {
        return $this->service->edit($request, $id);
    }

    public function update(UpdateExpenseCategoryRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy(DeletableRequest $request)
    {
        return $this->service->destroy($request);
    }
}
