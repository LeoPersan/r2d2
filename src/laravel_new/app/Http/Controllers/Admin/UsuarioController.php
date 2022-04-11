<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usuario\Salvar;
use App\Http\Requests\Usuario\Senha;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.usuarios.index', [
            'data' => Usuario::pesquisar($request)->paginate(config('app.list_count'))->appends($request->except('page')),
            'permissoes' => Usuario::PERMISSOES,
        ]);
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        return view('admin.usuarios.form', [
            'data' => $usuario,
            'permissoes' => Usuario::PERMISSOES
        ]);
    }

    public function create()
    {
        $usuario = new Usuario();
        $usuario->ativo = true;

        return view('admin.usuarios.form', [
            'data' => $usuario,
            'permissoes' => Usuario::PERMISSOES
        ]);
    }

    public function store(Salvar $request)
    {
        $usuario = Usuario::find($request->id) ?: new Usuario(['senha' => '123456']);
        $usuario->fill($request->validated());
        $usuario->save();

        return [
            'result'=> true,
            'msg' => 'Usuário '.($request->id ? 'alterado' : 'criado').' com sucesso!',
            'redirect' => route('usuarios.index')
        ];
    }

    public function trocarSenha()
    {
        return view('admin.usuarios.change_password', [
            'admin' => auth()->user()
        ]);
    }

    public function postTrocarSenha(Senha $request)
    {
        $usuario = auth()->user();
        $usuario->senha = $request->new_password;
        $usuario->save();

        return [
            'result' => true,
            'msg' => 'Senha alterada com sucesso!',
            'redirect' => route('admin')
        ];
    }

    public function meusDados()
    {
        return view('admin.usuarios.meus_dados', [
            'data' => auth()->user(),
        ]);
    }

    public function postMeusDados(Salvar $request)
    {
        $usuario = auth()->user();
        $usuario->fill($request->validated());
        $usuario->save();

        return [
            'result' => true,
            'msg' => 'Dados alterados com sucesso!',
            'redirect' => route('admin')
        ];
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return [
            'result' => true,
            'msg' => 'Usuário excluído com sucesso!',
            'redirect' => route('usuarios.index'),
        ];
    }
}
