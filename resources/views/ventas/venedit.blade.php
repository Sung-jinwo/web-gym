@extends('layout')

@section('title', 'Editar Venta')

@section('content')

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
                            <input class="filter-dropdown" type="number" name="cantidad" id="cantidad"
                                   value="{{ old('cantidad', optional($venta->detalles->first())->datelle_cantidad) }}"
                                   min="1" required placeholder="Cantidad de productos vendidos">
                        </div>

                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-dollar-sign"></i> Precio Unitario</label>
                            <input class="filter-dropdown" type="text" id="precio_unitario"
                                   value="{{ optional($venta->detalles->first())->datelle_precio_unitario ?? '0' }}"
                                   disabled>
                        </div>
                        
                    </div>
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-sort-numeric-up"></i> Precion incrementado
                            </label>
                            <input class="filter-dropdown" type="number" name="venta_incrementado" id="venta_incrementado"
                                value="{{ old('venta_incrementado', $venta->venta_incrementado ?? '') }}"
                             placeholder="Ingrese precio incrementado">
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
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-check-circle"></i> Estado del Pago
                            </label>
                            <select class="filter-dropdown" name="estado_venta" id="estado_venta" required>
                                <option value="">Seleccione el Pago</option>
                                <option value="Pagado"{{ old('estado_venta', $venta->estado_venta) == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                                <option value="Reservado"{{ old('estado_venta', $venta->estado_venta) == 'Reservado' ? 'selected' : '' }} >Reservado</option>
                            </select>
                            @if($errors->has('estado_pago'))
                                <span class="error-message">{{ $errors->first('estado_pago') }}</span>
                            @endif
                        </div>
                    </div>
                    {{-- Ventas reservadas --}}
                    <div id="campos-incompleto" class="payment-subsection"
                        style="display: {{ old('estado_venta', $venta->estado_venta ?? '') === 'Reservado' ? 'block' : 'none' }};">
                            <div class="subsection-header">
                                <h4 class="subsection-title">
                                    <i class="fa-solid fa-exclamation-triangle"></i>
                                    Ventas de Reserva
                                </h4>
                            </div>
                            <div class="filter-row">
                            <div class="filter-item">
                                <label for="venta_fecha" class="filter-label enhanced-label">
                                    <i class="fa-solid fa-clock"></i>
                                    Fecha Límite para Pagar
                                </label>
                                <input type="date" name="venta_fecha" id="venta_fecha"
                                    value="{{ old('venta_fecha', $venta->venta_fecha ? \Carbon\Carbon::parse($venta->venta_fecha)->format('Y-m-d') : '') }}"
                                    class="filter-dropdown enhanced-input">
                                @if($errors->has('venta_fecha'))
                                    <span class="error-message">{{ $errors->first('venta_fecha') }}</span>
                                @endif
                            </div>
                            <div class="filter-item">
                                <label for="venta_pago" class="filter-label enhanced-label">
                                    <i class="fa-solid fa-money-check"></i>
                                    Monto Pagado
                                </label>
                                <input type="number" name="venta_pago" id="venta_pago"
                                   value="{{ old('venta_pago', $venta->venta_pago ?? '') }}"
                                    step="0.01" class="filter-dropdown enhanced-input">
                                @if($errors->has('venta_pago'))
                                    <span class="error-message">{{ $errors->first('venta_pago') }}</span>
                                @endif
                            </div>
                        </div>
                            <div class="filter-item">
                                <label for="venta_saldo" class="filter-label enhanced-label">
                                    <i class="fa-solid fa-balance-scale"></i>
                                    Saldo Pendiente
                                </label>
                                <input type="number" name="venta_saldo" id="venta_saldo"
                                    value="{{ old('venta_saldo', $venta->venta_saldo ?? '') }}"
                                    step="0.01" class="filter-dropdown enhanced-input" readonly>
                                @if($errors->has('venta_saldo'))
                                    <span class="error-message">{{ $errors->first('venta_saldo') }}</span>
                                @endif
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
                        @if(auth()->user()->is(\App\Models\User::ROL_ADMIN) || auth()->user()->is(App\Models\User::ROL_EMPLEADO) )
        
                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-clock"></i> Turno del Trainer</label>
                            <select class="filter-dropdown" name="venta_entre">
                                <option value="" {{ old('venta_entre', $venta->venta_entre) == '' ? 'selected' : '' }}>Ninguno</option>
                                <option value="Trainer Mañana" {{ old('venta_entre', $venta->venta_entre) == 'Trainer Mañana' ? 'selected' : '' }}>Trainer Mañana</option>
                                <option value="Trainer Tarde" {{ old('venta_entre', $venta->venta_entre) == 'Trainer Tarde' ? 'selected' : '' }}>Trainer Tarde</option>
                            </select>
                        </div>
                        @endif
                        <div class="filter-item">
                            <label class="filter-label"><i class="fa-solid fa-location-dot"></i> Sede</label>
                            @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))

                            <select class="filter-dropdown" name="fksede" required>
                                <option value="">Seleccione Lugar de Registro</option>
                                @foreach ($sedes as $sede)
                                    <option value="{{ $sede->id_sede }}" {{ old('fksede', $venta->fksede) == $sede->id_sede ? 'selected' : '' }}>
                                        {{ $sede->sede_nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @else
                                <input class="filter-dropdown" type="text" value="{{ $venta->sede?->sede_nombre }}" readonly>
                                <input type="hidden" name="fksede" value="{{ $venta->fksede }}">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>






    <div class="form-actions-enhanced">
        <a href="{{route('venta.index')}}" class="btn btn-secondary">
            <i class="icono-volver"></i> Lista de Productos
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-cart-plus"></i>
            Actualizar Venta
        </button>
    </div>
    
</form>

<script>
    const estadoPagoSelect = document.getElementById('estado_venta');
    const camposIncompleto = document.getElementById('campos-incompleto');
    const fechaLimitePagoInput = document.getElementById('venta_fecha');
    const saldoPendienteInput = document.getElementById('venta_saldo');
    const montoPagadoInput = document.getElementById('venta_pago');
    const cantidadInput = document.querySelector('input[name="cantidad"]');
    const campoIncrementado = document.getElementById('venta_incrementado');
    const precioUnitario = {{ optional($venta->detalles->first())->datelle_precio_unitario ?? 0 }};
    const totalInput = document.getElementById('total');

    function calcularTotal() {
        const cantidad = parseFloat(cantidadInput.value) || 0;
        const incremento = parseFloat(campoIncrementado?.value) || 0;

        const total = (cantidad * precioUnitario) + incremento;
        totalInput.value = total.toFixed(2);
    }

    // Eventos
    cantidadInput.addEventListener('input', calcularTotal);
    campoIncrementado?.addEventListener('input', calcularTotal);


    // Calcular al cargar la página (por si hay valor inicial)
    window.addEventListener('DOMContentLoaded', calcularTotal);

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


    function toggleCamposIncompleto() {
                if (estadoPagoSelect.value === 'Reservado') {
                    // Mostrar los campos incompletos
                    camposIncompleto.style.display = 'block';
                } else {
                    // Ocultar y limpiar los campos incompletos
                    camposIncompleto.style.display = 'none';
                    fechaLimitePagoInput.value = '';
                    saldoPendienteInput.value = '0';
                    montoPagadoInput.value = '0';
                }
            }
    estadoPagoSelect.addEventListener('change', toggleCamposIncompleto);

</script>
@endsection
