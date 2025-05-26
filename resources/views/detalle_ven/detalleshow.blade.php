@extends('layout')

@section('title','Venta Detalle | '. ($detalle?$detalle->producto->prod_nombre: 'No encontrado'))

@section('content')
@include('partials.estado')

<div class="sale-container">
    <h2 class="sale-heading">Detalle de la venta</h2>

    <div class="sale-item">
        <span class="sale-label">Producto:</span>
        <span class="sale-value">{{$detalle->producto->prod_nombre}}</span>
    </div>

    <div class="sale-item">
        <span class="sale-label">Usuario De Venta:</span>
        <span class="sale-value">{{$detalle->venta->user->name}}</span>
    </div>
    <div class="sale-item">
        <span class="sale-label">Cliente:</span>
        <span class="sale-value">
            @if($detalle->venta->alumno)
                {{ $detalle->venta->alumno->alum_nombre.', '.$detalle->venta->alumno->alum_apellido }}
            @else
                Cliente sin registrar
            @endif
        </span>
    </div>
    <div class="sale-item">
        <span class="sale-label">Cantidad:</span>
        <span class="sale-value">{{$detalle->datelle_cantidad}}</span>
    </div>

    <div class="sale-item">
        <span class="sale-label">Precio Unitario:</span>
        <span class="sale-value">{{$detalle->datelle_precio_unitario}}</span>
    </div>

    <div class="sale-item">
        <span class="sale-label">Total de la Venta:</span>
        <span class="sale-value">{{$detalle->datelle_sub_total}}</span>
    </div>

    <div class="sale-item">
        <span class="sale-label">Sede:</span>
        <span class="sale-value">
            @if($detalle->venta)
                {{$detalle->venta->sede->sede_nombre}}
            @else
                {{ 'sede no disponible' }}
            @endif
        </span>
    </div>
    <div class="sale-item">
        <span class="sale-label">Dia y Hora de la venta:</span>
        <span class="sale-value">{{$detalle->venta->venta_fecha}}</span>
    </div>
    <div class="sale-actions">
        <a href="{{ route('detalle.index') }}" class="sale-btn sale-btn-warning">Volver</a>
    </div>
</div>
@endsection
