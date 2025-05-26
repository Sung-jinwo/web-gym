@extends('layout')

@section('title','Editar Membresia')

@section('content')


<h1>Editar Membresia</h1>

@include('partials.validation-errors')
<form  action="{{ route('membresias.update',$membresias) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @include('partials.formcateg',['btnText'=>'Actualizar'])
</form>

@endsection
