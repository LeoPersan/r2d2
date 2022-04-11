@extends('adminlte::page')

@section('content_header')
    <h1>Indicador</h1>
    <label>{{ $data->id ? $data->indicador_formatado : 'Novo Indicador' }}</label>
@endsection

@section('content')
    <div class="card" :json="[form.setData({{ $data }})]">
        <div class="card-body">
            <form @submit.prevent="$refs.submit.submit_form">
                <fieldset class="row">
                    <div class="form-group col-12">
                        <label>Indicador</label>
                        <select class="form-control" v-model="form.data.indicador" required>
                            @foreach (\App\Models\Indicador::INDICADORES as $chave => $indicador)
                            <option value="{{ $chave }}">{{ $indicador['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Data</label>
                        <the-mask type="tel" mask="##/##/####" class="form-control" v-model="form.data.data_formatada" required></the-mask>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Real</label>
                        <money class="form-control" type="tel" v-model="form.data.real" v-bind="money_config" required></money>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Dólar</label>
                        <money class="form-control" type="tel" v-model="form.data.dolar" v-bind="dollar_config" required></money>
                    </div>
                </fieldset>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary btn-block" onclick="history.back(-1)">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <submit-btn ref="submit" url="{{ route('indicadores.store') }}" >Salvar</submit-btn>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
