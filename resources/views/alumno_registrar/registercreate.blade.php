@extends('layout')

@section('title','Crear Prospecto')

@section('content')

<h1 class="alumno-edit-title">Crear Nuevo Prospecto</h1>

@include('partials.validation-errors')
<form  action="{{ route('registro.store') }}" method="POST" enctype="multipart/form-data" class="form-container">
    @include('partials.formregis',['btnText'=>'Guardar'])

</form>

@endsection
