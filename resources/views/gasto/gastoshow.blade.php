@extends('layout')

@section('title','Gasto Ver | '. ($gastos ? $gastos->gast_categoria: 'No encontrado'))

@section('content')
@include('partials.estado')

<div class="membresia-detail-container">
    <div class="membresia-detail-card">
        <div class="membresia-detail-header">
            <h2 class="membresia-detail-title">Detalles de Gastos</h2>
            <span class="membresia-detail-timestamp">
                @if($gastos && $gastos->created_at)
                    {{ $gastos->created_at->diffForHumans() }}
                @endif
            </span>
        </div>

        <div class="membresia-detail-content">
            <div class="membresia-detail-item">
                <label class="filter-label"><i class="icono-categoria"></i> Categoría:</label>
                <span class="membresia-detail-value">
                     @if($gastos->gast_categoria)
                            {{$gastos->gast_categoria}}
                    @else
                        {{ 'Categoría no disponible' }}
                    @endif
                </span>
            </div>

            <div class="membresia-detail-item">
                <label class="filter-label"><i class="fa-solid fa-align-left"></i> Descripcion de Gasto:</label>
                <span class="membresia-detail-value">
                    @if($gastos->gast_descripcion)
                        {{$gastos->gast_descripcion}}
                    @else
                        {{ 'Descripcion no disponible' }}
                    @endif
                </span>
            </div>

            <div class="membresia-detail-item">
                <label class="filter-label"><i class="fa-solid fa-location-dot"></i> Lugar de Gasto:</label>
                <span class="membresia-detail-value">
                    @if($gastos->sede)
                        {{$gastos->sede->sede_nombre}}
                    @else
                        {{ 'Lugar no disponible' }}
                    @endif
                </span>
            </div>

            <div class="membresia-detail-item">
                <label class="filter-label"><i class="icono-costo"></i> Costo:</label>
                <span class="membresia-detail-value membresia-detail-cost">{{ $gastos->gast_monto }}</span>
            </div>
        </div>

        <div class="membresia-detail-actions">
            @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                <a href="{{ route('gasto.edit', $gastos)}}" class="membresia-detail-btn membresia-detail-btn-edit">
                    <i class="icono-editar"></i> Editar Membresias
                </a>
            @endif
            <a href="{{ route('gasto.index')}}" class="membresia-detail-btn membresia-detail-btn-back">
                <i class="icono-volver"></i> Volver a la lista
            </a>
        </div>
    </div>
</div>


@endsection
