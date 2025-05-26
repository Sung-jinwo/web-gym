@extends('layout')

@section('title','Editar Categoria')
    
@section('content')

<h1>Editar Categoria</h1>

@include('partials.validation-errors') 

<form  action="{{ route('categoria.update',$categoria) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')  
    @include('partials.formvcato',['btnText'=>'Actualizar'])
</form>

@endsection