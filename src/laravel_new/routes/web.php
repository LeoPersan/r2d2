<?php

use App\Http\Controllers\Admin\AtualizarSenhaController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RecuperarSenhaController;
use App\Http\Controllers\Admin\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/recuperar_senha', [RecuperarSenhaController::class, 'showLinkRequestForm'])->name('recuperar_senha');
    Route::post('/recuperar_senha', [RecuperarSenhaController::class, 'sendResetLinkEmail']);
    Route::get('/atualizar_senha/{token}', [AtualizarSenhaController::class, 'showResetForm'])->name('atualizar_senha');
    Route::post('/atualizar_senha', [AtualizarSenhaController::class, 'reset']);
});
Route::group(['middleware' => ['auth:admin']], function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', [HomeController::class, 'index'])->name('admin');
    Route::post('upload_imagens', [HomeController::class, 'upload_imagens'])->name('upload_imagens');
    Route::get('meus_dados', [UsuarioController::class, 'meusDados'])->name('meus_dados');
    Route::post('meus_dados', [UsuarioController::class, 'postMeusDados']);
    Route::get('trocar_senha', [UsuarioController::class, 'trocarSenha'])->name('trocar_senha');
    Route::post('trocar_senha', [UsuarioController::class, 'postTrocarSenha']);
    Route::resource('usuarios', UsuarioController::class);
});
