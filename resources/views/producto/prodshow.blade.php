@extends('layout')

@section('title', 'Producto | ' . $producto->prod_nombre)

@section('content')
@include('partials.estado')

<div class="producto-detalle-unique-card">
    <div class="producto-detalle-unique-header">
        <h2 class="producto-detalle-unique-title">
            <i class="fa-solid fa-box-open"></i> Detalle de Producto
        </h2>
        @if($producto && $producto->created_at)
            <span class="producto-detalle-unique-date">
                <i class="fa-solid fa-calendar"></i> Registrado {{ $producto->created_at->diffForHumans() }}
            </span>
        @endif
    </div>

    <div class="producto-detalle-unique-content">
        <!-- Sección de imagen -->
        <div class="producto-detalle-unique-image-section">
            <div class="producto-detalle-unique-image-container">
                <img src="{{ asset('storage/' . $producto->prod_img) }}"
                     alt="Vista previa de {{ $producto->prod_nombre }}"
                     class="producto-detalle-unique-image">
            </div>
        </div>

        <!-- Sección de información -->
        <div class="producto-detalle-unique-info-section">
            <div class="producto-detalle-unique-info-grid">
                <div class="producto-detalle-unique-item">
                    <span class="producto-detalle-unique-label">
                        <i class="fa-solid fa-tag"></i> Nombre del Producto:
                    </span>
                    <span class="producto-detalle-unique-value">{{ $producto->prod_nombre ?? 'No disponible' }}</span>
                </div>

                <div class="producto-detalle-unique-item">
                    <span class="producto-detalle-unique-label">
                        <i class="fa-solid fa-layer-group"></i> Categoría:
                    </span>
                    <span class="producto-detalle-unique-value">{{ $producto->categoria->nombre ?? 'No disponible' }}</span>
                </div>

                <div class="producto-detalle-unique-item">
                    <span class="producto-detalle-unique-label">
                        <i class="fa-solid fa-cubes"></i> Cantidad:
                    </span>
                    <span class="producto-detalle-unique-value">{{ $producto->prod_cantidad ?? 'No disponible' }}</span>
                </div>

                <div class="producto-detalle-unique-item producto-detalle-unique-item-highlight">
                    <span class="producto-detalle-unique-label">
                        <i class="fa-solid fa-dollar-sign"></i> Precio:
                    </span>
                    <span class="producto-detalle-unique-value">${{ $producto->prod_precio ?? 'No disponible' }}</span>
                </div>

                <div class="producto-detalle-unique-item">
                    <span class="producto-detalle-unique-label">
                        <i class="fa-solid fa-user"></i> Usuario Registrado:
                    </span>
                    <span class="producto-detalle-unique-value">{{ $producto->user->name ?? 'No disponible' }}</span>
                </div>

                <div class="producto-detalle-unique-item">
                    <span class="producto-detalle-unique-label">
                        <i class="fa-solid fa-building"></i> Lugar del Producto:
                    </span>
                    <span class="producto-detalle-unique-value">{{ $producto->sede->sede_nombre ?? 'No disponible' }}</span>
                </div>
            </div>

            <!-- Descripción ocupa todo el ancho -->
            <div class="producto-detalle-unique-description">
                <div class="producto-detalle-unique-item producto-detalle-unique-item-full">
                    <span class="producto-detalle-unique-label">
                        <i class="fa-solid fa-file-alt"></i> Descripción:
                    </span>
                    <span class="producto-detalle-unique-value">{{ $producto->prod_descripcion ?? 'No disponible' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="producto-detalle-unique-actions">
        <a href="{{ route('producto.index') }}" class="producto-detalle-unique-btn producto-detalle-unique-btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver a los Productos
        </a>
        <a href="{{ route('producto.edit', $producto) }}" class="producto-detalle-unique-btn producto-detalle-unique-btn-primary">
            <i class="fa-solid fa-edit"></i> Editar Producto
        </a>
    </div>
</div>
@endsection
