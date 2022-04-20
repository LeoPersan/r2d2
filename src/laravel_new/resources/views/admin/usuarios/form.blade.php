@extends('adminlte::page')

@section('title_prefix', __('Usuários') . ' | ')

@section('content_header')
    <h1>Usuário</h1>
    <label>{{ $data->id ? $data->nome : 'Novo usuário - SENHA: 123456' }}</label>
@endsection

@section('content')
    <div class="card" :json="[form.setData({{ $data }})]">
        <div class="card-body">
            <form @submit.prevent="$refs.submit.submit_form">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nome</label>
                        <input type="text" class="form-control" v-model="form.data.nome" maxlength="190" required autofocus/>
                    </div>

                    <div class="form-group col-md-6">
                        <label>E-Mail</label>
                        <input type="email" class="form-control" v-model="form.data.email" maxlength="190" required/>
                    </div>

                    <div class="form-group col-12">
                        <label>Permissões</label>
                        <div class="row px-2">
                            @foreach ($permissoes as $permissao)
                            <div class="form-check col-md-4">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" v-model="form.data.permissoes" value="{{ $permissao }}"> {{ $permissao }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group col-12">
                        <label>Status</label>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" v-model="form.data.ativo" value="1"> Ativo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary btn-block" onclick="history.back(-1)">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <submit-btn ref="submit" url="{{ route('usuarios.store') }}" >Salvar</submit-btn>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
