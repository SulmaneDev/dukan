<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\NewLedgerService;
use Illuminate\Http\Request;

class NewLedgerController extends Controller
{
    public function index(Request $request, NewLedgerService $ledgerService)
    {
        return $ledgerService->index($request);
    }
}
