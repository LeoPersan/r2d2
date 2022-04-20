@extends('adminlte::page')

@section('title_prefix', __('Usuários'))

@section('content_header')
<div class="d-flex justify-content-between">
    <h1>Usuários</h1>
    <a href="{{ route('usuarios.create') }}"><button class="btn btn-dark">Novo Usuário</button></a>
</div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form class="row mt-2">
                <legend class="col-12">Pesquisa</legend>
                <div class="form-group col-sm-5 col-md-6">
                    <input type="text" name="nome_email" class="form-control" placeholder="Nome/Email" value="{{ request()->nome_email }}">
                </div>
                <div class="form-group col-sm-4 col-md-4">
                    <select class="form-control" name="permissao">
                        <option value="">Todas as Permissões</option>
                        @foreach ($permissoes as $permissao)
                            <option {{ request()->permissao == $permissao ? 'selected' : '' }}>{{ $permissao }}</option>
                        @endforeach
                    </select>
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
                        <th>Nome</th>
                        <th>Permissões</th>
                        <th width="20px"></th>
                        <th width="20px"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ implode(', ', $item->permissoes)}}</td>
                        <td>
                            <a href="{{ route('usuarios.edit', [$item->id]) }}" title="Editar">
                                <button class="btn btn-sm btn-outline-dark"><i class="fas fa-edit"></i></button>
                            </a>
                        </td>
                        <td>
                            @can('delete', $item)
                            <form @submit.prevent="destroy('{{ route('usuarios.destroy', [$item->id]) }}',$event)"
                                confirm="Você tem certeza que deseja excluir esse usuário?" ok="Sim" cancel="Não">
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
