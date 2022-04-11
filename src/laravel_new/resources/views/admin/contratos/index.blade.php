@extends('adminlte::page')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Contratos</h1>
        <a href="{{ route('contratos.create') }}"><button class="btn btn-dark">Novo Contrato</button></a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form class="row mt-2">
                <legend class="col-12">Pesquisa</legend>
                <div class="form-group col-sm-4">
                    <select name="cliente" class="form-control">
                        <option value="">Todos os Clientes</option>
                        @foreach ($clientes as $cliente)
                        <option {{ request()->cliente == $cliente->id ? 'selected' : '' }} value="{{ $cliente->id }}">{{ $cliente->nome_razao_social }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <select name="modelo" class="form-control">
                        <option value="">Todos os Modelos</option>
                        @foreach ($modelos as $modelo)
                        <option {{ request()->modelo == $modelo->id ? 'selected' : '' }} value="{{ $modelo->id }}">{{ $modelo->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <select class="form-control" name="etapa">
                        <option value="">Todas as Etapas</option>
                        @foreach (\App\Models\Contrato::ETAPAS as $etapa)
                            <option {{ request()->etapa == $etapa ? 'selected' : '' }}>{{ $etapa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group m-0 col-sm-2">
                    <button type="submit" class="btn btn-info float-right">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table id="table" class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Modelo</th>
                        <th>Etapa</th>
                        <th width="20px"></th>
                        <th width="20px"></th>
                        <th width="20px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->cliente->nome_razao_social }}</td>
                            <td>{{ $item->modelo->titulo }}</td>
                            <td>{{ $item->etapa_formatada }}</td>
                            <td>
                                <a target="_blank" href="{{ route('contratos.pdf', [$item->id]) }}" title="Contrato">
                                    <button class="btn btn-sm btn-outline-dark"><i class="fas fa-file-pdf"></i></button>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('contratos.edit', [$item->id]) }}" title="Editar">
                                    <button class="btn btn-sm btn-outline-dark"><i class="fas fa-edit"></i></button>
                                </a>
                            </td>
                            <td>
                                @can('delete', $item)
                                    <form @submit.prevent="destroy('{{ route('contratos.destroy', [$item->id]) }}',$event)"
                                        confirm="Você tem certeza que deseja excluir esse contrato?" ok="Sim" cancel="Não">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $data->links() }}
        </div>
    </div>
@endsection
