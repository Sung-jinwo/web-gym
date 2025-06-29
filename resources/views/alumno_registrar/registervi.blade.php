@extends('layout')

@section('title', 'Prospecto Redes')

@section('content')
@include('partials.validation-errors')

    <h1>Prospescto Redes</h1>

    <div class="actions-row">
        <a href="{{ route('registro.create')}}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Registrar
        </a>
    </div>

    <div class="search-panel">
        <h3 class="search-heading">Búsqueda</h3>

        <div class="filter-wrapper">
            <form method="GET" action="{{ route('registro.index') }}" class="filter-row">
                @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                    <div class="filter-item">
                        <label for="id_sede" class="filter-label">Sedes:</label>
                        <select name="id_sede" id="id_sede" class="filter-dropdown">
                            <option value="">Seleccionar</option>
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
                    <label for="estado" class="filter-label">Filtrar por estado:</label>
                    <select name="alum_estado" id="alum_estado" class="filter-dropdown">
                        <option value="A" {{ $filtroEstado == 'A' ? 'selected' : '' }}>Activos</option>
                        <option value="E" {{ $filtroEstado == 'E' ? 'selected' : '' }}>Eliminados</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="fecha_filtro" class="filter-label">Filtrar por Fecha:</label>
                    <input type="month"  id="fecha_filtro" name="fecha_filtro" class="filter-dropdown"
                           value="{{ old('fecha_filtro', $fechaFiltro) }}">
                </div>

                <div class="filter-item">
                    <label for="alumnoTexto" class="filter-label">Prospescto</label>
                    <input type="text" id="alumnoTexto" name="alumnoTexto" value="{{ request('alumnoTexto') }}"
                           placeholder="Buscar Prospescto" class="filter-dropdown">
                </div>


                <div class="filter-item filter-button-container">
                    <button type="submit" class="action-btn action-btn-primary">
                        <i class="fa-solid fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <table class="custom-table">
            <thead class="table-header">
            <tr>
                <th class="table-heading">Fecha de Registro</th>
                <th class="table-heading">Nombre</th>
                <th class="table-heading">Apellido</th>
                <th class="table-heading">Cantidad de interacciones</th>
                <th class="table-heading">Acciones</th>
            </tr>
            </thead>
            <tbody class="table-body">
            @if ($alumnos && $alumnos->count())
                @foreach ($alumnos as $alumno)
                    <tr class="table-row">
                        <td class="table-cell">
                            <span class="student-link">{{ $alumno->created_at }}</span>
                        </td>
                        <td class="table-cell">
                            <a href="{{ route('registro.show', $alumno->fkalum) }}" class="student-link">{{ $alumno->alumno->alum_nombre }}</a>
                        </td>
                        <td class="table-cell">
                            <a href="{{ route('registro.show', $alumno->fkalum) }}" class="student-link">{{ $alumno->alumno->alum_apellido }}</a>
                        </td>
                        <td class="table-cell">
                            <div class="interaction-container">
                                @for ($i = 1; $i <= 10; $i++)
                                    <div class="interaction-checkbox {{ $i <= $alumno->numero_mensaje ? 'checked ' . $alumno->color_cierre : '' }}" title="Interacción {{ $i }}"></div>
                                @endfor
                            </div>
                        </td>
                        <td class="table-cell actions-cell">
                            <div class="action-buttons">
                                @if ($alumno->alum_eda < 18)
                                    <span class="status status-active" title="Menor de Edad">
                                        <i class="icono-nino"></i>
                                    </span>
                                @else
                                    <span class="status status-inactive" title="Mayor de Edad">
                                        <i class="icono-usuario"></i>
                                    </span>
                                @endif
                                <a href="{{ route('registro.show', $alumno->fkalum) }}" class="btn-icon btn-blue" title="Ver detalles">
                                    <i class="icono-ver"></i>
                                </a>
                                <a href="{{ route('registro.edit', $alumno->fkalum) }}" class="btn-icon btn-green" title="Editar Prospescto">
                                    <i class="icono-editar"></i>
                                </a>
                                <a href="{{route('registro.conversar', $alumno)}}" class="btn-icon btn-rous" title="Conversar Prospescto">
                                    <i class="icono-mensaje"></i>
                                </a>
                                <form action="{{ route('registro.estado', $alumno) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('PUT')
                                    @if ($alumno->alumno->alum_estado == 'A')
                                        <button type="submit" class="btn-icon btn-red" title="Inhabilitar Prospescto" onclick="return confirm('¿Estás seguro de desactivar este alumno?')">
                                            <i class="icono-desactivar"></i>
                                        </button>
                                    @elseif($alumno->alumno->alum_estado == 'E')
                                        <button type="submit" class="btn-icon btn-green" title="Reactivar Prospescto" onclick="return confirm('¿Estás seguro de Activar este alumno?')">
                                            <i class="icono-activar"></i>
                                        </button>
                                    @endif
                                </form>
                                <form action="{{ route('registro.destroy', $alumno) }}" method="POST" class="inline-form">
                                    @csrf @method('DELETE')
                                    @if ($alumno->alumno->alum_estado == 'E')
                                        <button type="submit" class="btn-icon btn-red" title="Eliminar este Prospescto" onclick="return confirm('¿Estás seguro de eliminar al alumno?')">
                                            <i class="icono-eliminar"></i>
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="table-row">
                    <td colspan="7" class="table-cell no-results">No se encontró ningún alumno para mostrar</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="records-count">
            <p>Mostrando {{ $alumnos->count() }} de {{ $alumnos->total() }} registros</p>
        </div>
        <div class="pagination-container">
            {{ $alumnos->links('pagination::bootstrap-4') }}
        </div>
    </div>

@endsection
