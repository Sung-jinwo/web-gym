@extends('layout')

@section('title', 'Editar Gasto')

@section('content')


<h1 >Editar Gasto</h1>

@include('partials.validation-errors')
<form  action="{{ route('gasto.update',$gastos) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @include('partials.formgas',['btnText'=>'Actualizar'])
</form>


@endsection
