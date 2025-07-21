@extends('layout')

@section('title','Membresias | '. $membresias->mem_nomb)

@section('content')
@include('partials.estado')

<div class="membresia-detail-container">
    <div class="membresia-detail-card">
        <div class="membresia-detail-header">
            <h2 class="membresia-detail-title">Detalles de Membresía</h2>
            <span class="membresia-detail-timestamp">
                @if($membresias && $membresias->created_at)
                    {{ $membresias->created_at->diffForHumans() }}
                @endif
            </span>
        </div>

        <div class="membresia-detail-content">
            <div class="membresia-detail-item">
                <label class="filter-label"><i class="icono-categoria"></i> Categoría:</label>
                <span class="membresia-detail-value">
                 @if($membresias->categoria_m)
                    {{$membresias->categoria_m->nombre_m}}
                @else
                    {{ 'Categoría no disponible' }}
                @endif
                </span>
            </div>

            <div class="membresia-detail-item">
                <label class="filter-label"><i class="icono-nombre"></i> Nombre de membresia:</label>
                <span class="membresia-detail-value">{{ $membresias->mem_nomb}}</span>
            </div>

            <div class="membresia-detail-item">
                <label class="filter-label"><i class="fa-solid fa-calendar"></i> Dias: o (fecha)</label>
                <span class="membresia-detail-value">
                    @if ($membresias->mem_durac)
                    {{ $membresias->mem_durac }}
                    @else
                    {{ $membresias->fechalimite }}
                    @endif

                </span>
            </div>

            <div class="membresia-detail-item">
                <label class="filter-label"><i class="icono-costo"></i> Costo:</label>
                <span class="membresia-detail-value membresia-detail-cost">{{ $membresias->mem_cost }}</span>
            </div>
        </div>

        <div class="membresia-detail-actions">
            @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))

            <a href="{{ route('membresias.edit', $membresias)}}" class="membresia-detail-btn membresia-detail-btn-edit">
                <i class="icono-editar"></i> Editar Membresias
            </a>
            @endif
            <a href="{{ route('membresias.index')}}" class="membresia-detail-btn membresia-detail-btn-back">
                <i class="icono-volver"></i> Volver a la lista
            </a>
        </div>
    </div>
</div>

@endsection
