<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }

    public function upload_imagens(Request $request)
    {
        $imagem = $request->file('file');
        if (!$imagem instanceof UploadedFile)
            abort(422, 'Imagem inválida!');
        $filename = pathinfo($imagem->getClientOriginalName())['filename'].'_'.uniqid().'.'.$imagem->getClientOriginalExtension();
        $imagem->storeAs('editor', $filename, ['disk' => 'public']);
        return [
            'location' => '/storage/editor/'.$filename,
        ];
    }
}
