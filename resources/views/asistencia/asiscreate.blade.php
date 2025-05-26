@extends('layout')

@section('title','Crear Asistencia')

@section('content')

    <h1 >Crear Asistencia</h1>

@include('partials.validation-errors')

<form action="{{ route('asistencia.store') }}" method="POST" enctype="multipart/form-data">
    @include('partials.formasis',['btnText'=>'Guardar'])
</form>


@endsection
