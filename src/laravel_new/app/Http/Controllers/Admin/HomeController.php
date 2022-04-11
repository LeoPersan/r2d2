<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClienteDocumento;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }
}
