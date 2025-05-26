@extends('layout')

@section('title', 'Editar Pago')

@section('content')


    <h1 >Editar Pago</h1>

@include('partials.validation-errors')
<form  action="{{ route('pagos.update',$pago) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @include('partials.formpag',['btnText'=>'Actualizar'])
</form>


@endsection
