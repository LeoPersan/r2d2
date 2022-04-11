@extends('adminlte::page')

@section('content_header')
<div class="d-flex justify-content-between">
    <h1>Clientes</h1>
    <a href="{{ route('clientes.create') }}"><button class="btn btn-dark">Novo Cliente</button></a>
</div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form class="row mt-2">
                <legend class="col-12">Pesquisa</legend>
                <div class="form-group col-sm-4">
                    <select class="form-control" name="tipo">
                        <option value="">PF/PJ</option>
                        <option {{ request()->tipo == 'PF' ? 'selected' : '' }}>PF</option>
                        <option {{ request()->tipo == 'PJ' ? 'selected' : '' }}>PJ</option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <input type="text" name="nome_razao" class="form-control" placeholder="Nome/Razão Social" value="{{ request()->nome_razao }}">
                </div>
                <div class="form-group col-sm-4">
                    <select class="form-control" name="status">
                        <option value="">Ativos e Inativos</option>
                        <option {{ request()->status === "0" ? 'selected' : '' }} value="0">Apenas Inativos</option>
                        <option {{ request()->status === "1" ? 'selected' : '' }} value="1">Apenas Ativos</option>
                    </select>
                </div>
                <div class="form-group col-sm-4 col-md-5">
                    <the-mask type="tel" :mask="['###.###.###-##', '##.###.###/####-##']" name="cpf_cnpj" class="form-control" placeholder="CPF/CNPJ" value="{{ request()->cpf_cnpj }}"></the-mask>
                </div>
                <div class="form-group col-sm-5">
                    <input type="text" name="endereco" class="form-control" placeholder="UF/Cidade/Bairro/Endereço" value="{{ request()->endereco }}">
                </div>
                <div class="form-group m-0 col-sm-3 col-md-2">
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
                        <th>Tipo</th>
                        <th>Nome/Razão Social</th>
                        <th>CPF/CNPJ</th>
                        <th>Cidade/Estado</th>
                        <th width="20px"></th>
                        <th width="20px"></th>
                        <th width="20px"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->tipo }}</td>
                        <td>{{ $item->nome_razao_social }}</td>
                        <td>{{ $item->cpf_cnpj_formatado }}</td>
                        <td>{{ $item->cidade }}/{{ $item->uf }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-{{ $item->status ? 'success' : 'danger' }}" disabled>
                                <i class="fas fa-{{ $item->status ? 'check' : 'times' }}"></i>
                            </button>
                        </td>
                        <td>
                            <a href="{{ route('clientes.edit', [$item->id]) }}" title="Editar">
                                <button class="btn btn-sm btn-outline-dark"><i class="fas fa-edit"></i></button>
                            </a>
                        </td>
                        <td>
                            @can('delete', $item)
                            <form @submit.prevent="destroy('{{ route('clientes.destroy', [$item->id]) }}',$event)"
                                confirm="Você tem certeza que deseja excluir esse cliente?" ok="Sim" cancel="Não">
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
