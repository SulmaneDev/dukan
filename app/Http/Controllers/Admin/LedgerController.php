<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\LedgerService;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    protected LedgerService $service;

    function __construct(LedgerService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
       return $this->service->index($request);
    }
}
