@extends('layout')

@section('title','Venta Detalle | '. ($venta? $venta->productos->prod_nombre: 'No encontrado'))

@section('content')
@include('partials.estado')

<div class="sale-container">
    <h2 class="sale-heading">Detalle de la venta</h2>

    <div class="sale-item">
        <span class="sale-label">Producto:</span>
        <span class="sale-value">{{$venta->productos->prod_nombre}}</span>
    </div>

    <div class="sale-item">
        <span class="sale-label">Usuario De Venta:</span>
        <span class="sale-value">{{$venta->user->name}}</span>
    </div>
    <div class="sale-item">
        <span class="sale-label">Cliente:</span>
        <span class="sale-value">
            @if($venta->alumno)
                {{ $venta->alumno->alum_nombre.', '.$venta->alumno->alum_apellido }}
            @else
                Cliente sin registrar
            @endif
        </span>
    </div>
    <div class="sale-item">
        <span class="sale-label">Cantidad:</span>
        <span class="sale-value">{{$venta->cantidad}}</span>
    </div>

    <div class="sale-item">
        <span class="sale-label">Total de la Venta:</span>
        <span class="sale-value">{{$venta->venta_total}}</span>
    </div>

    <div class="sale-item">
        <span class="sale-label">Sede:</span>
        <span class="sale-value">
            @if($venta->sede)
                {{$venta->sede->sede_nombre}}
            @else
                {{ 'sede no disponible' }}
            @endif
        </span>
    </div>
    <div class="sale-item">
        <span class="sale-label">Dia y Hora de la venta:</span>
        <span class="sale-value">{{$venta->created_at->format('d/m/Y H:i:s')}}
        </span>
    </div>
    <div class="sale-actions">
        <a href="{{ route('venta.index') }}" class="sale-btn sale-btn-warning">Volver</a>
    </div>
</div>
@endsection
