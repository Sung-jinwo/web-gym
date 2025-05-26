@extends('layout')

@section('title','Ventas')

@section('content')

    <h1 >Listado de Ventas</h1>

    <div class="actions-row">
        <a href="{{ route('producto.index')}}" class="btn btn-secondary">
            <i class="icono-volver"></i> Regresar a Productos
        </a>
    </div>
<div class="venta-contenedor">

    <div class="padres-table-container">
        @if(count($detalle) > 0)
            <table class="padres-table">
                <thead class="padres-table-head">
                <tr>
                    <th class="padres-table-th">Producto</th>
                    <th class="padres-table-th">Sede</th>
                    <th class="padres-table-th">F. Venta</th>
                    <th class="padres-table-th">Cantidad</th>
                    <th class="padres-table-th">P. Unitario</th>
                    <th class="padres-table-th">Total</th>
                    <th class="padres-table-th padres-table-actions">Acciones</th>
                </tr>
                </thead>
                <tbody class="padres-table-body">
                @foreach ($detalle as $item)
                    <tr class="padres-table-row">
                        <td class="padres-table-td">
                            {{ $item->producto->prod_nombre }}
                        </td>
                        <td class="padres-table-td">
                            @if($item->venta)
                                {{$item->venta->sede->sede_nombre}}
                            @else
                                {{ 'sede no disponible' }}
                            @endif
                        </td>
                        <td class="padres-table-td">
                            {{$item->venta->venta_fecha}}
                        </td>
                        <td class="padres-table-td">
                            {{ $item->datelle_cantidad }}
                        </td>
                        <td class="padres-table-td">
                            {{ $item->datelle_precio_unitario }}
                        </td>
                        <td class="padres-table-td">
                            {{ $item->datelle_sub_total }}
                        </td>
                        <td class="padres-table-td padres-table-actions">
                            <div class="padres-action-buttons">
                                <a href="{{ route('detalle.show', $item) }}" class="padres-btn-icon padres-btn-edit" title="Ver Venta">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('venta.edit', $item->fkventa) }}" class="padres-btn-icon btn-green" title="Editar Producto">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="{{ route('detalle.boletapdf', $item) }}" class="padres-btn-icon btn-red" title="Descargar boleta (PDF)">
                                    <i class="fa-solid fa fa-file" ></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="venta-paginacion">
                {{ $detalle->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="padres-empty">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p>No hay registros de ventas disponibles</p>
            </div>
        @endif
    </div>

{{--  @if ($detalle && count($detalle) > 0)--}}
{{--    <div class="venta-lista">--}}
{{--      @foreach ($detalle as $item)--}}
{{--        <div class="venta-tarjeta">--}}
{{--          <div class="venta-info">--}}
{{--            <div class="venta-alumno">--}}
{{--              <a href="" class="enlace-accion">--}}
{{--                Alumno: {{$item->venta->alumno->alum_nombre ?? 'No disponible' }} {{$item->venta->alumno->alum_apellido ?? ''}}--}}
{{--              </a>--}}
{{--            </div>--}}
{{--            <div class="venta-sede">--}}
{{--              Sede:--}}

{{--            </div>--}}
{{--            <div class="venta-fecha">--}}
{{--              Fecha de venta:--}}
{{--            </div>--}}
{{--            <div class="venta-total">--}}
{{--              Total de la Venta:--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      @endforeach--}}
{{--    </div>--}}

{{--    <div class="venta-paginacion">--}}
{{--      {{ $detalle->links('pagination::bootstrap-4') }}--}}
{{--    </div>--}}
{{--  @else--}}
{{--    <div class="lista-vacia">--}}
{{--      <div class="lista-vacia-icono">📋</div>--}}
{{--      <p class="lista-vacia-texto">No hay registros de ventas disponibles</p>--}}
{{--    </div>--}}
{{--  @endif--}}
</div>
@endsection
