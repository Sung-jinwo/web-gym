@extends('layout')

@section('title', 'Pagos Incompletos')

@section('content')
@include('partials.estado')
@include('partials.validation-errors')

<h1><i class="fa-solid fa-list"></i> Listado de Pagos Incompletos</h1>


<div class="search-panel">
    <h3 class="search-heading">Búsqueda</h3>
    <div class="filter-wrapper">
        <form method="GET" action="{{ route('pagos.incompletos') }}" class="filter-row">
            @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                <div class="filter-item">
                    <label for="id_sede" class="filter-label">Sedes:</label>
                    <select name="id_sede" id="id_sede" class="filter-dropdown">
                        <option value="" >Seleccione una Sede</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id_sede }}"
                                {{ request('id_sede') == $sede->id_sede ? 'selected' : '' }}
                            >
                                {{ $sede->sede_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="filter-item">
                <label for="id_membresias" class="filter-label">Membresias:</label>
                <select name="id_membresias" id="id_membresias" class="filter-dropdown">
                    <option value="" >Seleccione una Membresias</option>
                    @foreach($membresias as $memmbresia)
                        <option value="{{ $memmbresia->id_mem }}"
                            {{ request('id_membresias') == $memmbresia->id_mem ? 'selected' : '' }}
                        >
                            {{ $memmbresia->mem_nomb }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="fecha_filtro" class="filter-label">Filtrar por Fecha:</label>
                <input type="month"  id="fecha_filtro" name="fecha_filtro" class="filter-dropdown"
                       value="{{ old('fecha_filtro', $fechaFiltro) }}">
            </div>



            <div class="filter-item">
                <label for="alumnoTexto" class="filter-label">Alumno</label>
                <input type="text" id="alumnoTexto" name="alumnoTexto" value="{{ request('alumnoTexto') }}"
                       placeholder="Buscar Alumno" class="filter-dropdown">
            </div>


            <div class="filter-item filter-button-container">
                <button type="submit" class="action-btn action-btn-primary">
                    <i class="fa-solid fa-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>




@if ($pagos && count($pagos))
    <div class="pagos-lista">
        @foreach ($pagos as $pago)
            <div class="pago-tarjeta">
                <a href="{{ route('pagos.show', $pago) }}" class="pago-enlace">
                    <div class="pago-info">
                        <div class="pago-campo">
                            <i class="fa-solid fa-user pago-icono"></i>
                            <span class="pago-etiqueta">Alumno:</span>
                            <span class="pago-valor">
                                {{ $pago->alumno->alum_nombre ?? 'No disponible' }}
                                {{ $pago->alumno->alum_apellido ?? '' }}
                            </span>
                        </div>
                        <div class="pago-campo">
                            <i class="fa-solid fa-id-card pago-icono"></i>
                            <span class="pago-etiqueta">Membresía:</span>
                            <span class="pago-valor">{{ $pago->membresia->mem_nomb }}</span>
                        </div>
                        <div class="pago-campo">
                            <i class="fa-solid fa-dollar-sign pago-icono"></i>
                            <span class="pago-etiqueta">Pago:</span>
                            <span class="pago-valor pago-monto">${{ number_format($pago->pago, 2) }}</span>
                        </div>
                        <div class="pago-campo">
                            <i class="fa-solid fa-building pago-icono"></i>
                            <span class="pago-etiqueta">Sede:</span>
                            <span class="pago-valor">{{ $pago->sede->sede_nombre ?? 'Sede no disponible' }}</span>
                        </div>
                        <div class="pago-campo">
                            <i class="fa-solid fa-calendar-check pago-icono"></i>
                            <span class="pago-etiqueta">Fecha Limite de Pago:</span>
                            <span class="pago-valor">{{ $pago->limite_formato?? 'Fecha no registrada' }}</span>
                        </div>
                        <div class="pago-campo">
                            <i class="fa-solid fa-money-bill-wave  pago-icono"></i>
                            <span class="pago-etiqueta">Saldo Pendiente:</span>
                            <span class="pago-valor">{{ $pago->saldo_pendiente ?? 'No tiene Saldo Pendiente' }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@else
    <div class="pagos-vacio">
        <div class="pagos-vacio-icono">
            <i class="fa-solid fa-receipt"></i>
        </div>
        <p class="pagos-vacio-texto">No hay registros de pagos disponibles</p>
        <p class="pagos-vacio-subtexto">Los pagos aparecerán aquí una vez que se registren</p>
    </div>
@endif

<div class="records-count">
    <p>Mostrando {{ $pagos->count() }} de {{ $pagos->total() }} registros</p>
</div>
<div class="pagos-unique-pagination">
    {{ $pagos->links('pagination::bootstrap-4') }}
</div>
@endsection
