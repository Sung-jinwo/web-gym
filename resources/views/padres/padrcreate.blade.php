@extends('layout')

@section('title', 'Crear Padre')

@section('content')

    <h1 >Crear Padre</h1>

@include('partials.validation-errors')
<form  action="{{ route('padres.store') }}" method="POST" enctype="multipart/form-data">
    @include('partials.formpadr',['btnText'=>'Guardar'])
</form>


@endsection
