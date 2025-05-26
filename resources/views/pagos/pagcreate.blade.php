@extends('layout')

@section('title', 'Generar Pago')

@section('content')

<h1 >Crear Pago</h1>

@include('partials.validation-errors')
<form  action="{{ route('pagos.store') }}" method="POST" enctype="multipart/form-data">
    @include('partials.formpag',['btnText'=>'Generar'])
</form>

@endsection
