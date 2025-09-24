@extends('layout')

@section('title', 'Listado de Reportes')

@section('content')
    <h1 >Ventas Registradas</h1>

    <div class="search-panel">
        <h3 class="search-heading">FIltrar Ventas</h3>
        <div class="filter-wrapper">
            <form method="GET" action="{{ route('reportes.ventas') }}" class="filter-row">
                @if(auth()->user()->is(\App\Models\User::ROL_ADMIN) )
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
                    <label for="metodos_pagos" class="filter-label">Metodos de Pago:</label>
                    <select name="metodos_pagos" id="metodos_pagos" class="filter-dropdown">
                        <option value="">Seleccionar</option>
                        @foreach($metodos_pagos as $metodos_pago)
                            <option value="{{ $metodos_pago->id_metod }}"
                                {{ request('metodos_pago') == $metodos_pago->id_metod ? 'selected' : '' }}
                            >
                                {{ $metodos_pago->tipo_pago }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <label for="fecha_filtro" class="filter-label">Filtrar por Fecha:</label>
                    <input type="date"  id="fecha_filtro" name="fecha_filtro" class="filter-dropdown"
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

    <div class="table-container">
        <table class="custom-table">
            <thead class="table-header">
                <tr>
                    <th class="table-heading">Usuario de pago</th>
                    <th class="table-heading">Metodo de Pago</th>
                    <th class="table-heading">Entrenador</th>
                    <th class="table-heading">Producto</th>
                    <th class="table-heading">Alumno</th>
                    <th class="table-heading">Cantidad</th>
                    <th class="table-heading">Precio Unitario</th>
                    <th class="table-heading">SubTotal</th>
                    <th class="table-heading">Estado</th>
                </tr>
            </thead>
            <tbody class="table-body">
                @if ($ventasvi && $ventasvi->count())
                    @foreach ($ventasvi as $ventas)
                    <tr class="table-row">
                        <td class="table-cell">
                            <h4>
                              <a class="student-link">{{ $ventas->venta->user->name }}</a>
                            </h4>
                        </td>
                        <td class="table-cell">
                            <a class="student-link">{{ $ventas->metodo->tipo_pago }}</a>
                        </td>
                        <td class="table-cell">
                            <a class="student-link">
                                @if ($ventas->venta->entrenador)
                                  
                                {{ $ventas->venta->venta_entre }}
                                    
                                @else
                                    sin entrenador
                                @endif
                            </a>
                        </td>
                        <td class="table-cell">
                            <a class="student-link">{{ $ventas->venta->productos->prod_nombre }}</a>
                        </td>
                        <td class="table-cell">
                            <a class="student-link">
                                @if ($ventas->venta->alumno)
                                    {{ $ventas->venta->alumno->nombre_completo }}
                                @else
                                    Sin Alumno
                                @endif
                            </a>
                        </td>
                        <td class="table-cell">
                            <a class="student-link">{{ $ventas->datelle_cantidad }}</a>
                        </td>
                        <td class="table-cell">
                            <a class="student-link">S/.{{ $ventas->datelle_precio_unitario }}</a>
                        </td>
                        <td class="table-cell">
                            <a class="student-link">S/.{{ $ventas->datelle_sub_total }}</a>
                        </td>
                        <td class="table-cell">
                            <a class="student-link">{{ $ventas->estado_venta }}</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr class="table-row">
                        <td colspan="9" class="table-cell no-results">No se encontró ningún pago para mostrar</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="records-count">
            <p>Mostrando {{ $ventasvi->count() }} de {{ $ventasvi->total() }} registros</p>
        </div>
        <div class="pagination-container">
            {{ $ventasvi->links('pagination::bootstrap-4') }}
        </div>
    </div>


@endsection