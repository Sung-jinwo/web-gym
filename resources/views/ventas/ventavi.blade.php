@extends('layout')

@section('title','Ventas')

@section('content')
@include('partials.estado')
@include('partials.validation-errors')

    <h1 >Listado de Ventas</h1>

    <div class="search-panel">
        <h3 class="search-heading">Búsqueda de los alumnos</h3>
        <div class="filter-wrapper">
            <form method="GET" action="{{ route('venta.index') }}" class="filter-row">

                <div class="filter-item">
                    <label for="id_producto" class="filter-label">Producto:</label>
                    <select name="id_producto" id="id_producto" class="filter-dropdown">
                        <option value="">Seleccionar Producto</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id_productos }}"
                                {{ request('id_producto') == $producto->id_productos ? 'selected' : '' }}
                            >
                                {{ $producto->prod_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
                    <label for="fecha_filtro" class="filter-label">Filtrar por Fecha:</label>
                    <input type="month" id="fecha_filtro" name="fecha_filtro" class="filter-dropdown"
                           value="{{ old('fecha_filtro', $fechaFiltro) }}">
                </div>

                <div class="filter-item filter-button-container">
                    <button type="submit" class="action-btn action-btn-primary">
                        <i class="fa-solid fa-search"> </i>Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

<div class="venta-contenedor">

    <div class="padres-table-container">
        @if(count($ventas) > 0)
            <table class="padres-table">
                <thead class="padres-table-head">
                <tr>
                    <th class="padres-table-th">Producto</th>
                    <th class="padres-table-th">Sede</th>
                    <th class="padres-table-th">F. Venta</th>
                    <th class="padres-table-th">Cantidad</th>
                    <th class="padres-table-th">Total</th>
                    <th class="padres-table-th padres-table-actions">Acciones</th>
                </tr>
                </thead>
                <tbody class="padres-table-body">
                @foreach ($ventas as $item)
                    <tr class="padres-table-row">
                        <td class="padres-table-td">
                            {{ $item->productos->prod_nombre }}
                        </td>
                        <td class="padres-table-td">
                            @if($item->sede)
                                {{$item->sede->sede_nombre}}
                            @else
                                {{ 'sede no disponible' }}
                            @endif
                        </td>
                        <td class="padres-table-td">
                            {{$item->created_at->format('d/m/Y H:i:s')}}
                        </td>
                        <td class="padres-table-td">
                            {{ number_format($item->cantidad, 2) }}
                        </td>
                        <td class="padres-table-td">
                            S/. {{ number_format($item->venta_total, 2) }}
                        </td>                        
                        <td class="padres-table-td padres-table-actions">
                            <div class="padres-action-buttons">
                                <a href="{{ route('venta.show', $item) }}" class="padres-btn-icon padres-btn-edit" title="Ver Venta">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('venta.edit', $item) }}" class="padres-btn-icon btn-green" title="Editar Producto">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                <a href="{{ route('detalle.boletapdf', $item->detalles->first()) }}" class="padres-btn-icon btn-red" title="Descargar boleta (PDF)">
                                    <i class="fa-solid fa fa-file" ></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="records-count">
                <p>Mostrando {{ $ventas->count() }} de {{ $ventas->total() }} registros</p>
            </div>
            <div class="venta-paginacion">
                {{ $ventas->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="padres-empty">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p>No hay registros de ventas disponibles</p>
            </div>
        @endif
    </div>

</div>
@endsection
