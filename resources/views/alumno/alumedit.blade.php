@extends('layout')

@section('title', 'Editar Alumno')

@section('content')




<h1 class="alumno-edit-title">Editar Alumno</h1>

@include('partials.validation-errors')

<form action="{{ route('alumno.update', $alumno) }}" method="POST" enctype="multipart/form-data" class="alumno-edit-form">
@method('PATCH')
@include('partials.formalum',['btnText'=>'Actualizar'])
</form>


@endsection
