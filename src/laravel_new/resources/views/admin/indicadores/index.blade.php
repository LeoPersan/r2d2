@extends('adminlte::page')

@section('content_header')
<div class="d-flex justify-content-between">
    <h1>Indicadores da CEPEA</h1>
</div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table id="table" class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th>Indicador</th>
                        <th>Data</th>
                        <th>Real</th>
                        <th>Dólar</th>
                        <th width="20px"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->indicador_formatado }}</td>
                        <td>{{ $item->data_formatada }}</td>
                        <td>{{ $item->real_formatado }}</td>
                        <td>{{ $item->dolar_formatado }}</td>
                        <td>
                            <a href="{{ route('indicadores.edit', [$item->id]) }}" title="Editar">
                                <button class="btn btn-sm btn-outline-dark"><i class="fas fa-edit"></i></button>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $data->links() }}
        </div>
    </div>
@endsection
