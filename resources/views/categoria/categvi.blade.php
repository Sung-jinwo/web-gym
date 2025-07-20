@extends('layout')

@section('title','Membresias')

@section('content')
@include('partials.estado')

{{--<div class="membresias-container">--}}
    <h1>Membresías</h1>
    <div class="actions-row">
        @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
            <a href="{{ route('membresias.create')}}" class="btn btn-primary">
                <i class="icono-nuevo"></i> Nueva Membresía
            </a>
        <a href="{{ route('catego_m.create')}}" class="btn btn-primary">
            <i class="icono-nuevo"></i> Nueva Categoria
        </a>
        @endif
    </div>
<div class="search-panel">
    <div class="filter-wrapper">
        <form method="GET" action="{{ route('membresias.index') }}" class="filter-row">
            @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                <div class="filter-item">
                    <label for="estado" class="filter-label">Membresia:</label>
                    <select name="estado" id="estado" class="filter-dropdown"  >
                        <option value="A" {{ $filtroEstado == 'A'  ? 'selected' : '' }}>Activo</option>
                        <option value="E" {{ $filtroEstado == 'E' ? 'selected' : '' }}>Eliminado</option>
                    </select>
                </div>
            @endif
                <div class="filter-item">
                    <label for="id_categoria" class="filter-label">Categoria:</label>
                    <select name="id_categoria" id="id_categoria" class="filter-dropdown">
                        <option value="" >Seleccione una Categoria</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria_m }}"
                                {{ request('id_categoria') == $categoria->id_categoria_m ? 'selected' : '' }}
                            >
                                {{ $categoria->nombre_m }}
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
                    <label for="membresiaTexto" class="filter-label">Nombre de Membresia</label>
                    <input type="text" id="membresiaTexto" name="membresiaTexto" value="{{ request('membresiaTexto') }}"
                           placeholder="Buscar Membresia" class="filter-dropdown">
                </div>

                <div class="filter-item filter-button-container">
                    <button type="submit" class="action-btn action-btn-primary">
                        <i class="fa-solid fa-search"></i> Buscar
                    </button>
                </div>
        </form>
    </div>
</div>
{{--<div class="filters-container">--}}

{{--</div>--}}

    <div class="table-container">
        <table class="membresias-table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Nombre</th>
                    <th>Meses(Dias) o Fecha </th>
                    <th>Costo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($membresias && count($membresias) > 0)
                @foreach ($membresias as $item)
                    <tr>
                        <td>
                            @if ($item->categoria_m)
                            <a href="{{ route('catego_m.edit', $item->categoria_m) }}" class="table-link">{{ $item->categoria_m->nombre_m }}</a>
                            @else
                                No Tiene categoria
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('membresias.show', $item) }}" class="table-link">{{$item->mem_nomb}}</a>
                        </td>
                        <td>
                            @if ($item->mem_limit)
                                Fecha Limite: {{ $item->fechalimite }}
                            @else
                                Dias: {{$item->mem_durac}}
                            @endif
                        </td>
                        <td>
                            S./{{$item->mem_cost}}
                        </td>
                        <td class="actions-cell">
                            <a href="{{ route('membresias.show', $item) }}" title="Ver Detalle" class="btn btn-blue">
                                <i class="icono-detalle"></i>
                            </a>
                            @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                            <a href="{{ route('membresias.edit', $item) }}" title="Editar" class="btn btn-green">
                                <i class="icono-editar"></i>
                            </a>
                            <form action="{{ route('membresias.cambiarEstado', $item) }}" method="POST" class="inline-form">
                                @csrf
                                @method('PUT')
                                @if ($item->estado == 'A')
                                    <button type="submit" class="btn btn-red" title="Inhabilitar membresia" onclick="return confirm('¿Estás seguro de desactivar este alumno?')">
                                        <i class="icono-eliminar"></i>
                                    </button>
                                @elseif($item->estado == 'E')
                                    <button type="submit" class="btn btn-green" title="Reactivar membresia" onclick="return confirm('¿Estás seguro de Activar este alumno?')">
                                        <i class="icono-activar"></i>
                                    </button>
                                @endif
                            </form>
{{--                                Form de Eliminar Definitivamente--}}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="empty-message">No hay ninguna membresía que mostrar</td>
                    </tr>
                @endif
            </tbody>

        </table>
        <div class="records-count">
            <p>Mostrando {{ $membresias->count() }} de {{ $membresias->total() }} registros</p>
        </div>
        <div class="pagination-container">
                {{$membresias->links('pagination::bootstrap-4')}}
        </div>
    </div>
{{--</div>--}}
@endsection
