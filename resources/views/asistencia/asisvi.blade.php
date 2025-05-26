@extends('layout')

@section('title','Asistencia')

@section('content')

{{--<div class="asist-list-container">--}}
{{--    <div class="asist-list-header">--}}
<h1 >Registro de Asistencias</h1>
<div class="actions-row">
    <a href="{{ route('asistencia.create')}}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nueva Asistencia</a>
</div>

<div class="search-panel">
    <h3 class="search-heading">Búsqueda</h3>

    <div class="filter-wrapper">
        <form method="GET" action="{{ route('asistencia.index') }}" class="filter-row">
            @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                <div class="filter-item">
                    <label for="sede_id" class="filter-label">Sedes:</label>
                    <select name="sede_id" id="sede_id" class="filter-dropdown">
                        <option value="" >Seleccione una Sede</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id_sede }}"
                                {{ request('sede_id') == $sede->id_sede ? 'selected' : '' }}
                            >
                                {{ $sede->sede_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif



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


{{--    </div>--}}

    @if ($asistencias && count($asistencias) > 0)
        <div class="asist-list-grid">
            @foreach ($asistencias as $item)
                <div class="asist-list-card">
                    <div class="asist-list-card-header">
                        {{ $item->alumno->alum_nombre ?? 'No disponible' }}
                        {{ $item->alumno->alum_apellido ?? '' }}
                    </div>
                    <div class="asist-list-card-body">
                        @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                        <div class="asist-list-card-row">
                            <div class="asist-list-card-label">Sede:</div>
                            <div class="asist-list-card-value">{{ $item->sede->sede_nombre ?? 'No disponible' }}</div>
                        </div>
                        @endif
                        <div class="asist-list-card-row">
                            <div class="asist-list-card-label">Fecha:</div>
                            <div class="asist-list-card-value">{{ $item->visi_fecha_formato }}</div>
                        </div>
                    </div>
                    <div class="asist-list-card-footer">
                        <a href="{{ route('asistencia.show', $item) }}" class="asist-list-view-button">Ver detalles</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="asist-list-empty">
            <div class="asist-list-empty-icon">📋</div>
            <p>No hay registros de asistencia disponibles</p>
        </div>
    @endif

    <div class="records-count">
        <p>Mostrando {{ $asistencias->count() }} de {{ $asistencias->total() }} registros</p>
    </div>
    <div class="pagination-container">
        {{ $asistencias->links('pagination::bootstrap-4') }}
    </div>
{{--</div>--}}

@endsection
