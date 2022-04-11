<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class AtualizarSenhaController extends Controller
{
    use ResetsPasswords;

    public $redirectTo = '/';

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    protected function setUserPassword($user, $password)
    {
        $user->senha = $password;
    }
}
