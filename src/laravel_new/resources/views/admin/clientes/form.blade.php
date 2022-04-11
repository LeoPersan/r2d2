@extends('adminlte::page')

@section('content_header')
    <h1>Cliente</h1>
    <label>{{ $data->id ? $data->nome : 'Novo cliente' }}</label>
@endsection

@section('content')
    <div class="card" :json="[form.setData({{ $data }})]">
        <div class="card-body">
            <form @submit.prevent="$refs.submit.submit_form">
                <fieldset class="row">
                    <div class="form-group col-md-6">
                        <label>CPF/CNPJ</label>
                        <the-mask type="tel" :mask="['###.###.###-##', '##.###.###/####-##']" v-model="form.data.cpf_cnpj" class="form-control" required></the-mask>
                    </div>

                    <div class="form-group col-md-6">
                        <label>E-mail</label>
                        <input type="email" class="form-control" v-model="form.data.email" maxlength="190" required/>
                    </div>
                </fieldset>
                <fieldset class="row mt-5" v-show="form.data.cpf_cnpj?.length == 11">
                    <legend>Pessoa Física</legend>
                    <div class="form-group col-md-6">
                        <label>Nome Completo</label>
                        <input type="text" class="form-control" v-model="form.data.nome_razao_social" maxlength="190"/>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Data de Nascimento</label>
                        <the-mask type="tel" mask="##/##/####" v-model="form.data.data_nascimento_formatada" class="form-control"></the-mask>
                    </div>

                    <div class="form-group col-md-3">
                        <label>RG</label>
                        <input type="tel" class="form-control" v-model="form.data.rg" maxlength="190"/>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Estado Civíl</label>
                        <input type="text" class="form-control" v-model="form.data.estado_civil" maxlength="190"/>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Profissão</label>
                        <input type="text" class="form-control" v-model="form.data.profissao" maxlength="190"/>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Naturalidade</label>
                        <input type="text" class="form-control" v-model="form.data.naturalidade" maxlength="190"/>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Nacionalidade</label>
                        <input type="text" class="form-control" v-model="form.data.nacionalidade" maxlength="190"/>
                    </div>
                </fieldset>
                <fieldset class="row mt-5" v-show="form.data.cpf_cnpj?.length == 14">
                    <legend>Pessoa Jurídica</legend>
                    <div class="form-group col-md-6">
                        <label>Razão Social</label>
                        <input type="text" class="form-control" v-model="form.data.nome_razao_social" maxlength="190"/>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Responsável</label>
                        <input type="text" class="form-control" v-model="form.data.responsavel" maxlength="190"/>
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
                <div class="row">
                    <div class="form-group col-12">
                        <label>Status</label>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" v-model="form.data.status" value="1"> Ativo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary btn-block" onclick="history.back(-1)">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <submit-btn ref="submit" url="{{ route('clientes.store') }}" >Salvar</submit-btn>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
