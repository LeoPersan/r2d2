@extends('adminlte::page')

@section('content_header')
    <h1>Modelo de Contrato</h1>
    <label>{{ $data->id ? $data->titulo : 'Novo modelo' }}</label>
@endsection

@section('content')
    <div class="card" :json="[form.setData({{ $data }}),form.setCustomData('apis', {{ $data->apis }}),form.setCustomData('variaveis_obrigatorias', {{ $variaveis_obrigatorias }})]">
        <div class="card-body">
            <form @submit.prevent="$refs.submit.submit_form">
                <fieldset class="row">
                    <div class="form-group col-12">
                        <label>Título</label>
                        <input type="text" class="form-control" v-model="form.data.titulo" maxlength="190"/>
                    </div>

                    <div class="form-group col-12">
                        <label>Modelo</label>
                        <editor api-key="{{ config('services.tiny.key') }}" v-model="form.data.modelo" :init="{
                            height: 200,
                            menubar: false,
                            plugins: [
                                'advlist autolink lists link image charmap print preview searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount'
                            ],
                            toolbar: 'undo redo | formatselect fontsizeselect | bold italic forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code removeformat | help'
                        }"></editor>
                        <div class="row px-2 p-1">
                            <span class="alert alert-info mb-0 mr-1 p-1" v-for="variavel in form.data.variaveis">@{{variavel}}</span>
                            <span class="alert alert-danger mb-0 mr-1 p-1" v-for="variavel in variaveis_nao_listadas">@{{variavel}}</span>
                        </div>
                    </div>

                    <div class="form-group col-12">
                        <label>Validações</label>
                        <div class="row">
                            <div class="col-md-6" v-for="api in form.apis">
                                <div class="row align-items-center">
                                    <div class="col-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" v-model="validacoes" :value="api.slug"> @{{ api.label }}
                                            </label>
                                        </div>
                                    </div>
                                    <div v-if="form.data.validacoes && form.data.validacoes[api.slug]" class="col-9">
                                        <template v-for="(label, variavel) in api.variables">
                                            <div class="form-group mb-0 row align-items-center">
                                                <label class="col-4 m-0 text-right"v-text="label"></label>
                                                <select class="col-8 form-control" v-model="form.data.validacoes[api.slug][variavel]" required>
                                                    <option v-for="variavel in form.data.variaveis" v-text="variavel"></option>
                                                </select>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-12">
                        <label>Status</label>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" v-model="form.data.status" value="1"> Ativo
                            </label>
                        </div>
                    </div>
                </fieldset>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary btn-block" onclick="history.back(-1)">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <submit-btn :disabled="variaveis_nao_listadas.length > 0" ref="submit" url="{{ route('modelos_contratos.store') }}" >Salvar</submit-btn>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
