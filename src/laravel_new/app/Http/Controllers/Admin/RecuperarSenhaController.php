<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class RecuperarSenhaController extends Controller
{
    use SendsPasswordResetEmails;
}
