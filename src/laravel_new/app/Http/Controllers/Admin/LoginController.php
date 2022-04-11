<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers {
        logout as traitLogout;
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('adminlte::auth.login');
    }

    public function redirectTo()
    {
        return route('admin');
    }

    public function logout(Request $request)
    {
        $this->traitLogout($request);
        return redirect(route('login'));
    }
}
