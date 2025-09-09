<?php

namespace App\Http\Controllers\Feature;

use App\Http\Controllers\Controller;

use App\Http\Requests\ScanImageRequest;
use App\Services\Feature\ScanService;

class ScanController extends Controller
{
    public function scan(ScanImageRequest $request, ScanService $scanner)
    {
        return $scanner->handle($request);
    }
}
