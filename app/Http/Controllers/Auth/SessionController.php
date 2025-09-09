<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\Auth\SessionService;

class SessionController extends Controller
{
    protected SessionService $service;

    function __construct(SessionService $service)
    {
        $this->service = $service;
    }

    public function login()
    {
        return  $this->service->login();
    }

    public function register()
    {
        return  $this->service->register();
    }
    public function handleLogin(LoginUserRequest $req)
    {
        return  $this->service->handleLogin($req);
    }

    public function handleRegister(RegisterUserRequest $req)
    {
        return  $this->service->handleRegister($req);
    }
}
