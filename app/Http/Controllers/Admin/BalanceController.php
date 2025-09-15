<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BalanceRequest;
use App\Http\Requests\Common\DeletableRequest;
use App\Services\Admin\BalanceService;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
      protected BalanceService $service;
    function __construct(BalanceService $service)
    {
        $this->service  = $service;
    }

    function index(Request $req)
    {
        return $this->service->index($req);
    }

    function edit(Request $req, string $id) {
        return $this->service->edit($req,$id);
    }

    function update(BalanceRequest $req, string $id) {
        return $this->service->update($req,$id);
    }

    function destroy(DeletableRequest $req) {
        return $this->service->destroy($req);
    }
}
