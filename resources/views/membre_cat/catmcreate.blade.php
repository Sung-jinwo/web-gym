@extends('layout')

@section('title','Crear Categoria')

@section('content')

    <h1 >Crear Nueva Categoria </h1>
    @include('partials.validation-errors')
    <form  action="{{ route('catego_m.store') }}" method="POST" enctype="multipart/form-data">
        @include('partials.formcatm',['btnText'=>'Guardar'])
    </form>


@endsection
