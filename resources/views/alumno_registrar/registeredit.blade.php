@extends('layout')

@section('title', 'Editar Prospecto')

@section('content')

<h1>Editar Prospecto</h1>

@include('partials.validation-errors')

<form action="{{ route('registro.update', $registros) }}" method="POST" enctype="multipart/form-data" >
    @method('PATCH')
    @include('partials.formregis',['btnText'=>'Actualizar'])
</form>


@endsection
