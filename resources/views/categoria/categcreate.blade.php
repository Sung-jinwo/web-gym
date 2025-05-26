@extends('layout')

@section('title','Crear Membresia')

@section('content')

<h1 >Crear Nueva Membresia</h1>

@include('partials.validation-errors')
<form  action="{{ route('membresias.store') }}" method="POST" enctype="multipart/form-data">
    @include('partials.formcateg',['btnText'=>'Guardar'])
</form>

@endsection
