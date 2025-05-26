@extends('layout')

@section('title','Editar Categoria')

@section('content')

<h1>
    <i class="fa-solid fa-edit"></i> Editar Categoría
</h1>


@include('partials.validation-errors')
<form  action="{{ route('catego_m.update',$categoria_m) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @include('partials.formcatm',['btnText'=>'Actualizar'])
</form>


    <div class="form-actions-enhanced">
        <form action="{{ route('catego_m.destroy', $categoria_m) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit"  class="btn btn-cancelar" onclick="return confirm('¿Estás seguro de eliminar esta Categoria?')">
                <i class="fa-solid fa-trash"></i> Eliminar Definitivamente
            </button>
        </form>
    </div>
@endsection
