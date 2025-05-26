@extends('layout')

@section('title','Editar Asistencia')
    
@section('content')

<h1>Editar Asistencia</h1>

@include('partials.validation-errors') 
<form  action="{{ route('asistencia.update',$asistencia) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')  
    @include('partials.formasis',['btnText'=>'Actualizar'])
</form>


@endsection
