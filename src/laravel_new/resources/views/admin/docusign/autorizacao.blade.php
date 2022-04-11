@extends('adminlte::auth.auth-page')

@section('auth_body')
    @if ($autorizacao)
        <h2>Authorização feita com sucesso</h2>
        <h4>Feche essa janela e envie novamente o Documento!</h4>
    @else
        <h2>Erro na authorização</h2>
        <h4>{{ $erro->getMessage() }}</h4>
    @endif
@stop
