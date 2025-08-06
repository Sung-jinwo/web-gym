@extends('layout')

@section('title','Alumno')

@section('content')

@include('partials.estado')
@include('partials.validation-errors')

    <h1 >Alumnos Registrados</h1>

    <div class="actions-row">
        <a href="{{ route('alumno.create')}}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Alumno
        </a>
        <a href="{{ route('padres.index') }}" class="btn btn-primary">
            <i class="fa-solid fa-user"></i> Lista de Padres
        </a>
    </div>

    <div class="search-panel">
        <h3 class="search-heading">Búsqueda de los alumnos</h3>
        <div class="filter-wrapper">
            <form method="GET" action="{{ route('alumno.index') }}" class="filter-row">
                @if(auth()->user()->is(\App\Models\User::ROL_ADMIN) || auth()->user()->is(App\Models\User::ROL_VENTAS) )
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
                    <select name="estado" id="estado" class="filter-dropdown">
                        <option value="A" {{ request('estado') == 'A' ? 'selected' : '' }}>Activos</option>
                        <option value="E" {{ request('estado') == 'E' ? 'selected' : '' }}>Eliminados</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="fecha_filtro" class="filter-label">Filtrar por inscripción:</label>
                    <select name="fecha_filtro" id="fecha_filtro" class="filter-dropdown">
                        <option value="">Seleccionar Inscripcion</option>
                        <option value="vigente" {{ request('fecha_filtro') == 'vigente' ? 'selected' : '' }}>Vigente</option>
                        <option value="por_caducar" {{ request('fecha_filtro') == 'por_caducar' ? 'selected' : '' }}>Por caducar / Renovar</option>
                        <option value="vencido" {{ request('fecha_filtro') == 'vencido' ? 'selected' : '' }}>Vencido</option>
                        <option value="sin_membresia" {{ request('fecha_filtro') == 'sin_membresia' ? 'selected' : '' }}>Sin membresía</option>
                    </select>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="estado_pago" class="filter-label">Filtrar por estado de pago:</label>
                    <select name="estado_pago" id="estado_pago" class="filter-dropdown">
                        <option value="">Seleccionar</option>
                        <option value="completo" {{ request('estado_pago') == 'completo' ? 'selected' : '' }}>Completos</option>
                        <option value="incompleto" {{ request('estado_pago') == 'incompleto' ? 'selected' : '' }}>Incompletos</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="alumnoTexto" class="filter-label">Alumno</label>
                    <input type="text" id="alumnoTexto" name="alumnoTexto" value="{{ request('alumnoTexto') }}"
                           placeholder="Buscar Alumno" class="filter-dropdown">
                </div>

                <div class="filter-item filter-button-container">
                    <button type="submit" class="action-btn action-btn-primary">
                        <i class="fa-solid fa-search"> </i>Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div class="table-container">
        <table class="custom-table">
            <thead class="table-header">
                <tr>
                    <th class="table-heading">Código</th>
                    <th class="table-heading">Foto</th>
                    <th class="table-heading">Nombre</th>
                    <th class="table-heading">Apellido</th>
                    <th class="table-heading">Estado Inscripción</th>
                    <th class="table-heading">Estado de Pago</th>
                    <th class="table-heading">Acciones</th>
                </tr>
            </thead>
            <tbody class="table-body">
                @if ($alumnos && $alumnos->count())
                    @foreach ($alumnos as $item)
                    <tr class="table-row">
                        <td class="table-cell">
                            <h4>
                              <a href="{{ route('alumno.show', $item) }}" class="student-link">{{ $item->alum_codigo }}</a>
                            </h4>
                        </td>
                        <td class="table-cell">
                            @if($item->alum_img)
                            <div class="image-container">
                                <img src="{{ $item->alum_img }}" alt="{{$item->alum_apellido }} {{ $item->alum_nombre}}" class="student-image">
                            </div>
                            @endif
                        </td>
                        <td class="table-cell">
                            <a href="{{ route('alumno.show', $item) }}" class="student-link">{{ $item->alum_nombre }}</a>
                        </td>
                        <td class="table-cell">
                            <a href="{{ route('alumno.show', $item) }}" class="student-link">{{ $item->alum_apellido }}</a>
                        </td>
                        <td class="table-cell">
                            <span class="status {{$item->clase_estado}}">{{ $item->texto_membresia }}</span>
                        </td>
                        <td class="table-cell">
                            <span class="status {{ $item->clasePago }}">
                                {{ ucfirst($item->estadoPago) }}
                            </span>
                        </td>
                        <td class="table-cell actions-cell">
                            <div class="action-buttons">
                                @if ($item->alum_eda < 18)
                                    @if ($item->padres->isEmpty())
                                        <a href="{{ route('padres.create') }}?alumno_id={{ $item->id_alumno }}" class="btn-icon btn-warning" title="Necesita agregar padre">
                                            <i class="icono-advertencia"></i>
                                        </a>
                                    @else
                                        <span class="status status-active" title="Menor de Edad">
                                            <i class="icono-nino"></i>
                                        </span>
                                    @endif
                                @else
                                    <span class="status status-edad" title="Mayor de Edad">
                                        <i class="icono-usuario"></i>
                                    </span>
                                @endif
                                <a href="{{ route('alumno.show', $item) }}" class="btn-icon btn-blue" title="Ver detalles">
                                    <i class="icono-ver"></i>
                                </a>
                                <a href="{{ route('alumno.edit', $item) }}" class="btn-icon btn-green" title="Editar alumno">
                                    <i class="icono-editar"></i>
                                </a>
                                <form action="{{ route('alumno.cambiarEstado', $item) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('PUT')
                                    @if ($item->alum_estado == 'A')
                                        <button type="submit" class="btn-icon btn-red" title="Inhabilitar alumno" onclick="return confirm('¿Estás seguro de desactivar este alumno?')">
                                            <i class="icono-desactivar"></i>
                                        </button>
                                    @elseif($item->alum_estado == 'E')
                                        <button type="submit" class="btn-icon btn-green" title="Reactivar alumno" onclick="return confirm('¿Estás seguro de Activar este alumno?')">
                                            <i class="icono-activar"></i>
                                        </button>
                                    @endif
                                </form>
                                @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                                <form action="{{ route('alumno.destroy', $item) }}" method="POST" class="inline-form">
                                    @csrf @method('DELETE')
                                    @if ($item->alum_estado == 'E')
                                        <button type="submit" class="btn-icon btn-red" title="Eliminar alumno" onclick="return confirm('¿Estás seguro de eliminar al alumno?')">
                                            <i class="icono-eliminar"></i>
                                        </button>
                                @endif
                                </form>
                                @endif
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
