@extends('layout')

@section('title', 'Editar Venta')

@section('content')
@include('partials.estado')
@include('partials.validation-errors')

    <h1>Editar Venta</h1>

<form class="sale-form" action="{{ route('venta.update', $venta) }}" method="POST">
    @csrf
    @method('PUT')
    <!-- ID oculto -->
    <input type="hidden" name="fkalum" id="fkalum" value="{{ old('fkalum', $venta->fkalum) }}">

    <div class="filter-row">
        <div class="filter-item">    <!-- Sección: Información del Alumno -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos"><i class="fa-solid fa-user-graduate"></i> Información del Alumno</h3>
                    <p class="section-description">Datos del alumno asociado a la venta registrada.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">


                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-id-card"></i> Código del Alumno</label>
                            <input class="filter-dropdown" type="text" id="alum_codigo" name="alum_codigo"
                                   value="{{ old('alum_codigo', $venta->alumno?->alum_codigo ?? 'Alumno no registrado') }}"
                                   placeholder="Ingrese código del alumno">
                            @if ($errors->has('alum_codigo'))
                                <span class="sale-form-error">{{ $errors->first('alum_codigo') }}</span>
                            @endif
                        </div>

                        <div class="filter-item filter-button-container">
                            <button type="button" id="buscarAlumno" class="action-btn action-btn-primary">
                                <i class="fa-solid fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-user-graduate"></i> Nombre Completo del Alumno</label>
                            <input class="filter-dropdown" type="text" id="nombre_alumno" name="nombre_alumno"
                                   value="{{ old('nombre_alumno', isset($venta->alumno) ? $venta->alumno->alum_nombre . ' ' . $venta->alumno->alum_apellido : '') }}"
                                   readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-item">

            <!-- Sección: Detalle del Producto -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos"><i class="fa-solid fa-box-open"></i> Detalle del Producto</h3>
                    <p class="section-description">Datos del producto asociado a la venta.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-box-open"></i> Producto</label>
                            <input class="filter-dropdown" type="text"
                                   value="{{ optional($venta->detalles->first()->producto)->prod_nombre ?? 'Producto no disponible' }}"
                                   disabled>
                        </div>

                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-sort-numeric-up"></i> Cantidad</label>
                            <input class="filter-dropdown" type="number" name="cantidad"
                                   value="{{ old('cantidad', optional($venta->detalles->first())->datelle_cantidad) }}"
                                   min="1" required placeholder="Cantidad de productos vendidos">
                        </div>

                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-dollar-sign"></i> Precio Unitario</label>
                            <input class="filter-dropdown" type="text"
                                   value="{{ optional($venta->detalles->first())->datelle_precio_unitario ?? '0' }}"
                                   disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="filter-row">
        <div class="filter-item">
            <!-- Sección: Información de Pago -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos"><i class="fa-solid fa-credit-card"></i> Información de Pago</h3>
                    <p class="section-description">Verifica el monto total y selecciona el método de pago.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-money-bill-wave"></i> Total a pagar</label>
                            <input class="filter-dropdown" type="text" id="total" value="{{ $venta->venta_total }}" readonly>
                        </div>

                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-credit-card"></i> Tipo de Pago</label>
                            <select class="filter-dropdown" name="fkmetodo" required>
                                <option value="">Seleccione el Pago</option>
                                @foreach ($metodos as $metodo)
                                    <option value="{{ $metodo->id_metod }}" {{ old('fkmetodo', $venta->fkmetodo) == $metodo->id_metod ? 'selected' : '' }}>
                                        {{ $metodo->tipo_pago }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-item">

            <!-- Sección: Datos del Registro -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos"><i class="fa-solid fa-clipboard-user"></i> Datos del Registro</h3>
                    <p class="section-description">Información del responsable de la venta y su ubicación.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-user"></i> Usuario</label>
                            @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                                <select class="filter-dropdown" name="fkusers" required>
                                    <option value="">Seleccione un Usuario</option>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('fkusers', $venta->fkusers) == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="fkusers" value="{{ auth()->user()->id }}">
                                <input class="filter-dropdown" type="text" value="{{ auth()->user()->name }}" disabled>
                            @endif
                        </div>

                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-clock"></i> Turno del Trainer</label>
                            <select class="filter-dropdown" name="venta_entre">
                                <option value="" {{ old('venta_entre', $venta->venta_entre) == '' ? 'selected' : '' }}>Ninguno</option>
                                <option value="Trainer Mañana" {{ old('venta_entre', $venta->venta_entre) == 'Trainer Mañana' ? 'selected' : '' }}>Trainer Mañana</option>
                                <option value="Trainer Tarde" {{ old('venta_entre', $venta->venta_entre) == 'Trainer Tarde' ? 'selected' : '' }}>Trainer Tarde</option>
                            </select>
                        </div>

                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-location-dot"></i> Sede</label>
                            <select class="filter-dropdown" name="fksede" required>
                                <option value="">Seleccione Lugar de Registro</option>
                                @foreach ($sedes as $sede)
                                    <option value="{{ $sede->id_sede }}" {{ old('fksede', $venta->fksede) == $sede->id_sede ? 'selected' : '' }}>
                                        {{ $sede->sede_nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>






    <div class="form-actions-enhanced">
        <a href="{{route('detalle.index')}}" class="btn btn-secondary">
            <i class="icono-volver"></i> Lista de Productos
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-cart-plus"></i>
            Actualizar Venta
        </button>
    </div>
</form>

<script>
    document.getElementById('buscarAlumno').addEventListener('click', function () {
        const codigo = document.getElementById('alum_codigo').value;

        if (!codigo || codigo.length !== 4) {
            alert('El código del alumno debe tener exactamente 4 dígitos.');
            return;
        }

        // Realizar la solicitud AJAX para buscar el alumno
        fetch(`/alumno/buscar?codigo=${codigo}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar los campos del formulario
                    document.getElementById('fkalum').value = data.alumno.id_alumno; // ID del alumno
                    document.getElementById('nombre_alumno').value = data.alumno.nombre_completo; // Nombre completo
                } else {
                    alert('No se encontró ningún alumno con ese código.');
                    document.getElementById('fkalum').value = ''; // Limpiar el ID
                    document.getElementById('nombre_alumno').value = ''; // Limpiar el nombre
                }
            })
            .catch(error => {
                console.error('Error al buscar el alumno:', error);
                alert('Ocurrió un error al buscar el alumno.');
            });
    });
</script>
@endsection
