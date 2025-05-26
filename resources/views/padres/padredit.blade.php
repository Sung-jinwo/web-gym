@extends('layout')

@section('title','Editar Padre')
    
@section('content')


<h1>Editar Padre</h1>

@include('partials.validation-errors') 
<form  action="{{ route('padres.update',$padre->id_padre) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')  
    @include('partials.formpadr',['btnText'=>'Actualizar'])
</form>

@endsection