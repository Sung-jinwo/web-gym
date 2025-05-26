@extends('layout')

@section('title','Crear Alumno')

@section('content')


<h1 class="alumno-edit-title">Crear nuevo alumno</h1>

@include('partials.validation-errors')
<form  action="{{ route('alumno.store') }}" method="POST" enctype="multipart/form-data">
    @include('partials.formalum',['btnText'=>'Guardar'])

</form>

@endsection
