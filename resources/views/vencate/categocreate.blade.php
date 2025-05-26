@extends('layout')

@section('title','Crear Categoria')
    
@section('content')

<h1>Crear Nueva Categoria Producto</h1>
@include('partials.validation-errors') 
<form  action="{{ route('categoria.store') }}" method="POST" enctype="multipart/form-data">
    @include('partials.formvcato',['btnText'=>'Guardar'])
</form>



@endsection
