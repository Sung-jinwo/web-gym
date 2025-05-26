@extends('layout')

@section('title', 'Categoría')

@section('content')
{{--<div class="categoria-unique-container">--}}
<h1>
    <i class="fa-solid fa-tags"></i> Categorías de Productos
</h1>


<div class="actions-row">
    <a href="{{ route('producto.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Ver Listado de Productos
    </a>
</div>


@if ($categoria && count($categoria))
    <div class="pagos-lista">
        @foreach ($categoria as $item)
            <div class="pago-tarjeta">
                <a href="{{ route('categoria.edit', $item) }}" class="pago-enlace">
                    <div class="pago-info">
                        <div class="pago-campo">
                            <i class="fa-solid fa-user pago-icono"></i>
                            <span class="pago-etiqueta">Nombre de Categoría:</span>
                            <span class="pago-valor">
                                {{ $item->nombre }}
                            </span>
                        </div>
                        <div class="pago-campo">
                            <i class="fa-solid fa-id-card pago-icono"></i>
                            <span class="pago-etiqueta">Usuario de Registro:</span>

                            <span class="pago-valor"> {{ $item->user->name ?? 'No disponible' }}</span>
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
    <p>Mostrando {{ $categoria->count() }} de {{ $categoria->total() }} registros</p>
</div>
<div class="pagos-unique-pagination">
    {{ $categoria->links('pagination::bootstrap-4') }}
</div>


@endsection
