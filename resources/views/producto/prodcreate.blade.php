@extends('layout')

@section('title','Crear Producto')

@section('content')

<h1>Crear Nuevo Producto</h1>
@include('partials.validation-errors')
<form  action="{{ route('producto.store') }}" method="POST" enctype="multipart/form-data">
    @include('partials.formprod',['btnText'=>'Guardar'])

</form>

@endsection
