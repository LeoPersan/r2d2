@extends('adminlte::page')

@section('title_prefix', __('Trocar Senha') . ' | ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="login-box">
            <div class="card">
                <div class="card-header">Alteração de senha</div>
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Para alterar sua senha, informe-as abaixo</p>

                    <form @submit.prevent="$refs.submit.submit_form">
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Senha Atual" v-model="form.data.current_password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Senha nova" v-model="form.data.new_password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Confirme a senha nova" v-model="form.data.new_password_confirmation" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <submit-btn url="{{route('trocar_senha')}}" ref="submit">Salvar senha</submit-btn>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
