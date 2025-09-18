<?php

namespace App\Services\Auth;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SessionService
{
    public function login()
    {
        return view('pages.auth.login');
    }

    public function register()
    {
        return view('pages.auth.register');
    }

    public function handleLogin(LoginUserRequest $request)
    {
        $data = $request->validated();
        if (!Auth::attempt($data)) {
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'message' => 'Invalid credentials',
                'title' => "Error",
            ]);
        };
        $request->session()->regenerate();
        return redirect()->route('admin.brand.index')->with('alert', [
            'type' => 'success',
            'title' => "Success",
            'message' => "Login successfully.",
        ]);
    }
    public function handleRegister(RegisterUserRequest $req)
    {
        $data = $req->validated();
        $user = new User();
        $user->username = $data['username'] ?? "";
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = 'user';
        $user->save();
        return redirect()->route('auth.login')->with('alert', [
            'type' => 'success',
            'title' => "Success",
            'message' => "Register successfully.",
        ]);
    }
}
