<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Services\Admin\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected BrandService $service;
    function __construct(BrandService $service)
    {
        $this->service = $service;
    }

    public function index(Request $req) {
        return $this->service->index($req);
    }

    public function create(Request $req) {
        return $this->service->create($req);
    }

    public function store(CreateBrandRequest $req) {
        return $this->service->store($req);
    }
    public function edit(Request $req,string $id)  {
        return $this->service->edit($req,$id);
    }
    public function update(UpdateBrandRequest $req,string $id)  {
        return $this->service->update($req,$id);
    }
    public function destroy(DeletableRequest $rq) {
        return $this->service->destroy($rq);
    }
}
