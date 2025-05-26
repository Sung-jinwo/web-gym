@extends('layout')

@section('title', 'Generar un Gasto')

@section('content')

<h1>Crear un Gasto</h1>

@include('partials.validation-errors')
<form  action="{{ route('gasto.store') }}" method="POST" enctype="multipart/form-data">
@include('partials.formgas',['btnText'=>'Generar'])
</form>

@endsection
