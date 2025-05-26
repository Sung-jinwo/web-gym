@extends('layout')

@section('title','Gastos')

@section('content')
    @include('partials.estado')
    @include('partials.validation-errors')
<h1>Gastos Registrados </h1>

<div class="actions-row">
    <a href="{{ route('gasto.create')}}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Nuevo Gasto
    </a>
</div>

<div class="search-panel">
    <h3 class="search-heading">Búsqueda de un Gasto</h3>
    <div class="filter-wrapper">
        <form method="GET" action="{{ route('gasto.index') }}" class="filter-row">
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
                <label for="categoria" class="filter-label">Filtrar por Categoria:</label>
                <select name="categoria" id="categoria" class="filter-dropdown">
                    <option value="">Seleccione una Categoria </option>
                    <option value="Electricidad" {{ old('categoria',$categoria) == 'Electricidad' ? 'selected' : '' }}>Electricidad</option>
                    <option value="Agua" {{ old('categoria', $categoria) == 'Agua' ? 'selected' : '' }}>Agua</option>
                    <option value="Internet" {{ old('categoria', $categoria) == 'Internet' ? 'selected' : '' }}>Internet</option>
                    <option value="Limpieza" {{ old('categoria', $categoria) == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                    <option value="Sueldos" {{ old('categoria', $categoria) == 'Sueldos' ? 'selected' : '' }}>Sueldos</option>
                    <option value="Alimentos" {{ old('categoria', $categoria) == 'Alimentos y suplementos' ? 'selected' : '' }}>Alimentos y suplementos</option>
                    <option value="Utensilios" {{ old('categoria', $categoria) == 'Utensilios deportivos' ? 'selected' : '' }}>Utensilios deportivos</option>
                    <option value="Mantenimiento de equipos" {{ old('categoria', $categoria) == 'Mantenimiento de equipos' ? 'selected' : '' }}>Mantenimiento de equipos</option>
                    <option value="Reparaciones generales" {{ old('categoria', $categoria) == 'Reparaciones generales' ? 'selected' : '' }}>Reparaciones generales</option>
                    <option value="Papelería y oficina" {{ old('categoria',$categoria) == 'Papelería y oficina' ? 'selected' : '' }}>Papelería y oficina</option>
                    <option value="Capacitación del personal" {{ old('categoria', $categoria) == 'Capacitación del personal' ? 'selected' : '' }}>Capacitación del personal</option>
                    <option value="Otros" {{ old('categoria', $categoria) == 'Otros' ? 'selected' : '' }}>Otros</option>
                </select>
            </div>



            <div class="filter-item">
                <label for="fecha_filtro" class="filter-label">Filtrar por Fecha:</label>
                <input type="date"  id="fecha_filtro" name="fecha_filtro" class="filter-dropdown"
                       value="{{ old('fecha_filtro', $fechaFiltro) }}"
                >
            </div>

{{--            <div class="filter-item">--}}
{{--                <label for="categoria" class="filter-label">Nombre del Gasto</label>--}}
{{--                <input type="text" id="DescripcionText" name="categoria" value="{{ request('categoria') }}"--}}
{{--                       placeholder="Buscar Gasto" class="filter-dropdown">--}}
{{--            </div>--}}

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
                <th class="table-heading">Tipo de gasto</th>
                <th class="table-heading">Descripcion de gasto</th>
                <th class="table-heading">Monto</th>
                <th class="table-heading">Acciones</th>
            </tr>
            </thead>
            <tbody class="table-body">
            @if ($gastos && $gastos->count())
                @foreach ($gastos as $gasto)
                    <tr class="table-row">
                        <td class="table-cell">
                            <h4>
                                <a href="{{ route('gasto.show', $gasto) }}" class="student-link">{{ $gasto->gast_categoria }}</a>
                            </h4>
                        </td>
                        <td class="table-cell">
                            <a href="{{ route('gasto.show', $gasto) }}" class="student-link">{{ $gasto->gast_descripcion }}</a>
                        </td>
                        <td class="table-cell">
                            <a href="{{ route('gasto.show', $gasto) }}" class="student-link">{{ $gasto->gast_monto }}</a>
                        </td>
                        <td class="table-cell actions-cell">
                            <div class="action-buttons">
                                <a href="{{ route('gasto.show', $gasto) }}" class="btn-icon btn-blue" title="Ver detalles">
                                    <i class="icono-ver"></i>
                                </a>
                                @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                                <a href="{{ route('gasto.edit', $gasto) }}" class="btn-icon btn-green" title="Editar gasto">
                                    <i class="icono-editar"></i>
                                </a>
                                    <form action="{{ route('gasto.destroy', $gasto) }}" method="POST" class="inline-form">
                                        @csrf @method('DELETE')
                                        {{--                                    @if ($gasto->alum_estado == 'E')--}}
                                        <button type="submit" class="btn-icon btn-red" title="Eliminar gasto" onclick="return confirm('¿Estás seguro de eliminar al alumno?')">
                                            <i class="icono-eliminar"></i>
                                        </button>
                                        {{--                                    @endif--}}
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="table-row">
                    <td colspan="7" class="table-cell no-results">No se encontró ningún Gasto para mostrar</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="pagination-container">
            {{ $gastos->links('pagination::bootstrap-4') }}
        </div>
    </div>


@endsection
