@extends('layout')

@section('title', 'Productos')

@section('content')
@include('partials.estado')
@include('partials.validation-errors')
    <h1 ><i class="fa-solid fa-boxes-stacked"></i> Productos</h1>

        <!-- Filtro por Estado -->
        <div class="producto-unique-filtro">
            <form method="GET" action="{{ route('producto.index') }}" class="producto-unique-filtro-form">
                <label for="estado" class="producto-unique-filtro-label">
                    <i class="fa-solid fa-filter"></i> Filtrar por 
                </label>
                @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                    Estado:
                    <select name="estado" id="estado" class="producto-unique-filtro-select"
                            onchange="this.form.submit()">
                        <option value="activo" {{ $filtroEstado == 'activo' ? 'selected' : '' }}>Activos</option>
                        <option value="inactivo" {{ $filtroEstado == 'inactivo' ? 'selected' : '' }}>Inactivos</option>

                    </select>
                @endif
                @if(auth()->user()->is(\App\Models\User::ROL_ADMIN) || auth()->user()->is(App\Models\User::ROL_VENTAS))
                
                    <label for="id_sede" class="producto-unique-filtro-label">
                        Sedes:
                    </label>
                    <select name="id_sede" id="id_sede" class="producto-unique-filtro-select"
                            onchange="this.form.submit()">
                        <option value="">Seleccionar</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id_sede }}"
                                {{ request('id_sede') == $sede->id_sede ? 'selected' : '' }}>
                                {{ $sede->sede_nombre }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </form>
        </div>

        <!-- Acciones -->
        <div class="producto-unique-acciones">
            @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                <a href="{{ route('producto.create') }}" class="producto-unique-btn producto-unique-btn-primary">
                    <i class="fa-solid fa-plus"></i> Nuevo Producto
                </a>
                <a href="{{ route('categoria.create') }}" class="producto-unique-btn producto-unique-btn-secondary">
                    <i class="fa-solid fa-tags"></i> Nueva Categoría
                </a>
                <a href="{{ route('categoria.index') }}" class="producto-unique-btn producto-unique-btn-secondary">
                    <i class="fa-solid fa-list"></i> Listado de Categorías
                </a>
            @endif
        </div>


        <!-- Lista de Productos -->
        @if ($producto && count($producto) > 0)
            <div class="producto-unique-lista">
                @foreach ($producto as $item)
                    <div class="producto-unique-tarjeta">
                        <div class="producto-unique-imagen-contenedor">
                            <img src="{{ asset($item->prod_img) }}"
                                 alt="Vista previa de {{ $item->prod_nombre }}" class="producto-unique-imagen">
                        </div>
                        <div class="producto-unique-info">
                            <div class="producto-unique-nombre">
                                <i class="fa-solid fa-box"></i> {{ $item->prod_nombre }}
                            </div>
                            <div class="producto-unique-precio">
                                <i class="fa-solid fa-dollar-sign"></i> {{ $item->prod_precio }}
                            </div>
                            <div class="producto-unique-cantidad">
                                <i class="fa-solid fa-cubes"></i> Cantidad:
                                @if ($item->isStockCritico())
                                    <span class="producto-unique-badge producto-unique-badge-peligro">{{ $item->prod_cantidad }} (Stock Crítico)</span>
                                @else
                                    <span>{{ $item->prod_cantidad }}</span>
                                @endif
                            </div>
                            <div class="producto-unique-estado">
                                <i class="fa-solid fa-circle"></i> Estado:
                                @if ($item->estado === 'activo')
                                    <span class="producto-unique-badge producto-unique-badge-exito">Activo</span>
                                @else
                                    <span class="producto-unique-badge producto-unique-badge-peligro">Inactivo</span>
                                @endif
                            </div>

                            <div class="producto-unique-precio">
                                <i class="fa-solid fa-building" ></i>Lugar:
                                {{$item->sede?->sede_nombre}}
                            </div>
                        </div>

                        <div class="producto-unique-acciones">
                            @if (!$item->isStockCritico())
                                <a href="{{ route('venta.create', $item->id_productos) }}" title="Generar Venta"
                                   class="producto-unique-btn producto-unique-btn-primary">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                            @else
                                <span class="producto-unique-btn producto-unique-btn-deshabilitado"
                                      title="Generar Venta (Stock Insuficiente)">
                                <i class="fa-solid fa-cart-plus"></i>
                            </span>
                            @endif

                            <a href="{{ route('producto.show', $item) }}"
                               class="producto-unique-btn producto-unique-btn-secondary" title="Ver Detalles">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            @if ($item->estado === 'activo' && auth()->user()->is(\App\Models\User::ROL_ADMIN))
                                <form action="{{ route('producto.destroy', $item) }}" method="POST"
                                      class="producto-unique-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="producto-unique-btn producto-unique-btn-peligro"
                                            title="Inhabilitar"
                                            onclick="return confirm('¿Estás seguro de inhabilitar este producto?')">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                            @elseif(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                                <form action="{{ route('producto.activar', $item) }}" method="POST"
                                      class="producto-unique-form">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="producto-unique-btn producto-unique-btn-exito"
                                            title="Activar"
                                            onclick="return confirm('¿Estás seguro de activar este producto?')">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="producto-unique-paginacion">
                {{ $producto->appends(['estado' => $filtroEstado])->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="producto-unique-lista-vacia">
                <div class="producto-unique-lista-vacia-icono">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <p>No hay registros de productos disponibles</p>
            </div>
        @endif
@endsection
