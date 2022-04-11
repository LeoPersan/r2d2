@extends('adminlte::page')

@section('content_header')
    <h1>Configurações</h1>
@endsection

@section('content')
    <div class="card" :json="[form.setData({{ $data }})]">
        <div class="card-body">
            <form @submit.prevent="$refs.submit.submit_form">
                <fieldset class="row">
                    <div class="form-group col-md-4">
                        <label>Razão Social</label>
                        <input type="text" class="form-control" v-model="form.data.razao_social" maxlength="190" required autofocus/>
                    </div>

                    <div class="form-group col-md-4">
                        <label>CNPJ</label>
                        <the-mask type="tel" mask="##.###.###/####-##" v-model="form.data.cnpj" class="form-control" required></the-mask>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Responsavel</label>
                        <input type="text" class="form-control" v-model="form.data.responsavel" maxlength="190" required/>
                    </div>
                </fieldset>
                <fieldset class="row mt-5">
                    <legend>Endereço</legend>
                    <div class="form-group col-md-4">
                        <label>CEP</label>
                        <the-mask type="tel" mask="#####-###" v-model="form.data.cep" class="form-control"></the-mask>
                    </div>

                    <div class="form-group col-md-2">
                        <label>UF</label>
                        <input class="form-control" v-model="form.data.uf" disabled/>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Cidade</label>
                        <input class="form-control" v-model="form.data.cidade" disabled/>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Bairro</label>
                        <input type="text" class="form-control" v-model="form.data.bairro" maxlength="190" required/>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Endereço</label>
                        <input type="text" class="form-control" v-model="form.data.endereco" maxlength="190" required/>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Número</label>
                        <input type="tel" class="form-control" v-model="form.data.numero" maxlength="190" required/>
                    </div>

                    <div class="form-group col-12">
                        <label>Complemento</label>
                        <input type="text" class="form-control" v-model="form.data.complemento" maxlength="190"/>
                    </div>
                </fieldset>
                <fieldset class="row mt-5">
                    <legend>Certificado</legend>
                    <div class="form-group col-md-6">
                        <label>Arquivo</label>
                        <Arquivo v-model="form.data.certificado" url="{{ route('configuracoes.upload_certificado') }}" :required="!form.data.certificado" accept=".pfx"></Arquivo>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Senha</label>
                        <input type="password" class="form-control" v-model="form.data.senha"/>
                    </div>
                </fieldset>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary btn-block" onclick="history.back(-1)">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <submit-btn ref="submit" url="{{ route('configuracoes.store') }}" >Salvar</submit-btn>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
