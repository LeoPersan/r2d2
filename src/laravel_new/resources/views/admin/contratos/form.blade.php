@extends('adminlte::page')

@section('content_header')
    <h1>Contrato</h1>
    <label>{{ $data->id ? $data->titulo : 'Novo modelo' }}</label>
@endsection

@section('content')
    <div class="card" :json="[form.setData({{ $data }}),form.setCustomData('modelos', {{ $modelos }}),form.setCustomData('clientes', {{ $clientes }}),form.setCustomData('variaveis_obrigatorias', {{ $variaveis_obrigatorias }})]">
        <div class="card-body">
            <form @submit.prevent="$refs.submit.submit_form">
                <fieldset>
                        <div class="form-group">
                            <label>Etapa</label>
                            <select class="form-control" v-model="form.data.etapa">
                                @foreach (\App\Models\Contrato::ETAPAS as $chave => $etapa)
                                <option value="{{ $chave }}">{{ $etapa }}</option>
                                @endforeach
                            </select>
                        </div>
                </fieldset>
                <template v-if="form.data.etapa == 'confeccao'">
                    <fieldset class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cliente</label>
                                <v-select v-model="form.data.cliente_id" :options="{{ json_encode($clientes) }}" label="nome_razao_social" :reduce="cliente => cliente.id"/>
                            </div>

                            <div class="form-group">
                                <label>Modelo de Contrato</label>
                                <v-select v-model="form.data.modelo_contrato_id" :options="{{ json_encode($modelos) }}" label="titulo" :reduce="modelo => modelo.id"/>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Garantias</label>
                            <editor api-key="{{ config('services.tiny.key') }}" v-model="form.data.garantias" :init="{
                                height: 200,
                                menubar: false,
                                plugins: [
                                    'advlist autolink lists link image charmap print preview searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount'
                                ],
                                toolbar: 'undo redo | formatselect fontsizeselect | bold italic forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code removeformat | help'
                            }"></editor>
                        </div>
                    </fieldset>
                    <fieldset class="row" v-show="form.data.modelo_contrato_id">
                        <legend>Dados do Contrato</legend>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group col-12" v-for="campo in campos">
                                    <label>@{{ campo }}</label>
                                    <input type="text" class="form-control" v-model="form.data.dados_contrato[campo]" @change="setDadosContrato">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" v-html="form.data.contrato"></div>
                    </fieldset>
                </template>
                <template v-if="form.data.etapa == 'confirmacao'">
                    <fieldset>
                        <div class="form-group">
                            <label>Contrato</label>
                            <editor api-key="{{ config('services.tiny.key') }}" v-model="form.data.contrato" :init="{
                                height: 200,
                                menubar: false,
                                plugins: [
                                    'advlist autolink lists link image charmap print preview searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount'
                                ],
                                toolbar: 'undo redo | formatselect fontsizeselect | bold italic forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code removeformat | help'
                            }"></editor>
                        </div>
                    </fieldset>
                </template>
                <template v-if="form.data.etapa == 'validacao'">
                    <div class="accordion" id="validacoes">
                        @foreach ($data->validacoes ?? [] as $validacao)
                        <div class="card">
                            <div class="card-header bg-gradient-blue" id="heading{{ $validacao->slug }}">
                                <h2 class="mb-0 d-flex justify-content-between">
                                    <button class="btn btn-link text-left text-white" type="button" data-bs-toggle="collapse" @click.prevent.stop="form.show{{$validacao->slug}} = !form.show{{$validacao->slug}};$forceUpdate()"
                                        data-bs-target="#collapse{{ $validacao->slug }}">
                                        <i v-if="!form.show{{$validacao->slug}}" class="fas fa-plus"></i>
                                        <i v-else class="fas fa-minus"></i>
                                        {{ $validacao->nome }}
                                    </button>
                                    <button type="button" class="btn bg-gradient-green text-center text-white" @click.prevent.stop="api('{{ route('contratos.validar.api', [$data->id, $validacao->slug]) }}', '{{ $validacao->slug }}')">
                                        Validar pela API
                                    </button>
                                    <button type="button" class="btn bg-gradient-green text-center text-white" @click.prevent.stop="importarArquivo('{{ route('contratos.validar.arquivo', [$data->id, $validacao->slug]) }}', '{{ $validacao->slug }}')">
                                        Importar arquivo
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse{{ $validacao->slug }}" class="collapse" aria-labelledby="heading{{ $validacao->slug }}" data-parent="#validacoes">
                                <div class="card-body">
                                    {{ $validacao->resultado }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </template>
                <template v-if="form.data.etapa == 'primeira_assinatura'">
                    <div class="accordion" id="primeira_assinatura">
                        <div class="card">
                            <div class="card-header bg-gradient-blue" id="headingprimeira_assinatura">
                                <h2 class="mb-0 d-flex justify-content-between">
                                    <button class="btn btn-link text-left text-white" type="button" data-bs-toggle="collapse" @click.prevent.stop="form.showprimeira_assinatura = !form.showprimeira_assinatura;$forceUpdate()"
                                        data-bs-target="#collapseprimeira_assinatura">
                                        <i v-if="!form.showprimeira_assinatura" class="fas fa-plus"></i>
                                        <i v-else class="fas fa-minus"></i>
                                        Assinatura do Proprietário
                                    </button>
                                    @if ($data->cliente_id)
                                    <button type="button" class="btn bg-gradient-green text-center text-white" @click.prevent.stop="pedirAssinatura('{{ route('contratos.pedido_assinatura', [$data->id, 'cliente', $data->cliente_id]) }}', 'primeira_assinatura')">
                                        Enviar pedido de assinatura para o cliente
                                    </button>
                                    @endif
                                </h2>
                            </div>
                            <div id="collapseprimeira_assinatura" class="collapse" aria-labelledby="headingprimeira_assinatura" data-parent="#primeira_assinatura">
                                <div class="card-body">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary btn-block" onclick="history.back(-1)">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <submit-btn ref="submit" url="{{ route('contratos.store') }}" >Salvar</submit-btn>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
