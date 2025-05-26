@extends('layout')

@section('title','Editar Producto')
    
@section('content')

<h1>Editar Producto</h1>


@include('partials.validation-errors') 
<form  action="{{ route('producto.update',$producto) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')  
    @include('partials.formprod',['btnText'=>'Actualizar'])
</form>


@endsection