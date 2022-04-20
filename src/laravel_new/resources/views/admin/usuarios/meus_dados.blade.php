@extends('adminlte::page')

@section('title_prefix', __('Meus Dados') . ' | ')

@section('content')
    <div class="card" :json="[form.setData({{ $data }})]">
        <div class="card-header">
           {{ __('Usuário') }} [ {{ $data->id ? $data->name : __('Novo usuário - SENHA: 123456') }} ]
        </div>
        <div class="card-body">
            <form @submit.prevent="$refs.submit.submit_form">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>{{ __('Nome') }}</label>
                        <input type="text" class="form-control" v-model="form.data.nome" required autofocus/>
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ __('E-mail') }}</label>
                        <input type="email" class="form-control" v-model="form.data.email" required/>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary btn-block" onclick="history.back(-1)">{{ __('Cancelar') }}</button>
                    </div>
                    <div class="col-md-6">
                        <submit-btn ref="submit" url="{{ route('meus_dados') }}" >{{ __('Salvar') }}</submit-btn>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
