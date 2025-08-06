@extends('layout')

@section('title', 'Generar Venta')

@section('content')
<h1>Generar Venta</h1>

<form   class="sale-form"  action="{{ route('venta.store') }}" method="POST">
    @csrf

    <div class="filter-row">
        <div class="filter-item">
            <!-- Sección: Información del Alumno -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos">
                        <i class="fa-solid fa-user-graduate"></i> Información del Alumno
                    </h3>
                    <p class="section-description">Verifica los datos del alumno antes de registrar la venta.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <input type="hidden" name="fkproducto" value="{{ $producto->id_productos }}">
                        <input type="hidden" id="fkalumno" name="fkalum" value="{{ old('fkalum') }}">

                        <div class="filter-item">
                            <label class="filter-label" for="alum_codigo">
                                <i class="fa-solid fa-id-card"></i> Código del Alumno
                            </label>
                            <input class="filter-dropdown" type="text" id="alum_codigo" name="alum_codigo"
                                   value="{{ old('alum_codigo', isset($alumno->alum_codigo) ? $alumno->alum_codigo : '') }}"
                                   pattern="\d{4}" maxlength="4"
                                   placeholder="Ingrese código de 4 dígitos" title="El código debe contener exactamente 4 dígitos">
                            @if ($errors->has('fkalum'))
                                <span class="sale-form-error">{{ $errors->first('fkalum') }}</span>
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
                            <label class="filter-label" for="nombre_alumno">
                                <i class="fa-solid fa-user-graduate"></i> Nombre Completo del Alumno
                            </label>
                            <input class="filter-dropdown" type="text" id="nombre_alumno" name="nombre_alumno"
                                   value="{{ old('fkalum', isset($alumno->alumno) ? $alumno->alum_nombre.' '.$alumno->alum_apellido : '') }}"
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
                    <h3 class="Sub_titulos">
                        <i class="fa-solid fa-box"></i> Detalle del Producto
                    </h3>
                    <p class="section-description">Revisa el producto seleccionado y define la cantidad a vender.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-box-open"></i> Producto
                            </label>
                            <input class="filter-dropdown" type="text" value="{{ $producto->prod_nombre }}" disabled>
                        </div>

                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-sort-numeric-up"></i> Cantidad
                            </label>
                            <input class="filter-dropdown" type="number" name="cantidad"
                                   min="1" max="{{ $producto->prod_cantidad }}"
                                   required placeholder="Ingrese cantidad a vender">
                        </div>

                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-dollar-sign"></i> Precio Unitario
                            </label>
                            <input class="filter-dropdown" type="text" value="{{ $producto->prod_precio }}" disabled>
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-sort-numeric-up"></i> Precion incrementado
                            </label>
                            <input class="filter-dropdown" type="number" name="venta_incrementado" id="venta_incrementado"
                             placeholder="Ingrese precio incrementado">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="filter-row">
        <div class="filter-item">
            <!-- Sección: Pago -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos">
                        <i class="fa-solid fa-money-check-dollar"></i> Información de Pago
                    </h3>
                    <p class="section-description">Indica el método de pago y verifica el total calculado automáticamente.</p>
                </div>

                    <div class="section-content">
                        <div class="filter-row">
                            <div class="filter-item">
                                <label class="filter-label">
                                    <i class="fa-solid fa-money-bill-wave"></i> Total a pagar
                                </label>
                                <input class="filter-dropdown" type="text" id="total" readonly placeholder="Total calculado automáticamente">
                            </div>

                            <div class="filter-item">
                                <label class="filter-label">
                                    <i class="fa-solid fa-credit-card"></i> Tipo de Pago
                                </label>
                                <select class="filter-dropdown" name="fkmetodo" required>
                                    <option value="">Seleccione el Pago</option>
                                    @foreach ($metodos as $metodo)
                                        <option value="{{ $metodo->id_metod }}">{{ $metodo->tipo_pago }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="filter-row">
                            <div class="filter-item">
                                <label class="filter-label">
                                    <i class="fa-solid fa-credit-card"></i> Método de Pago 2 (adicional)
                                </label>
                                <select class="filter-dropdown" name="fkmetodo_2">
                                    <option value="">Seleccione método</option>
                                    @foreach ($metodos as $metodo)
                                        <option value="{{ $metodo->id_metod }}">{{ $metodo->tipo_pago }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="filter-item">
                                <label class="filter-label">
                                    <i class="fa-solid fa-dollar-sign"></i> Monto 2 (adicional) </label>
                                <input type="number" name="monto_2" step="0.01" class="filter-dropdown">
                            </div>
                        </div>
                        <div class="filter-row">
                            <div class="filter-item">
                                <label class="filter-label">
                                    <i class="fa-solid fa-check-circle"></i> Estado del Pago
                                </label>
                                <select class="filter-dropdown" name="estado_venta" id="estado_venta" required>
                                    <option value="">Seleccione el Pago</option>
                                    <option value="Pagado">Pagado</option>
                                    <option value="Reservado">Reservado</option>
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
                                        value=""
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
                                        value=""
                                        step="0.01" class="filter-dropdown enhanced-input">
                                    @if($errors->has('venta_pago'))
                                        <span class="error-message">{{ $errors->first('venta_pago') }}</span>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="filter-item">
                                <label for="saldo_pendiente" class="filter-label enhanced-label">
                                    <i class="fa-solid fa-balance-scale"></i>
                                    Saldo Pendiente
                                </label>
                                <input type="number" name="saldo_pendiente" id="saldo_pendiente"
                                    value=""
                                    step="0.01" class="filter-dropdown enhanced-input" readonly>
                                @if($errors->has('saldo_pendiente'))
                                    <span class="error-message">{{ $errors->first('saldo_pendiente') }}</span>
                                @endif
                            </div> --}}
                        </div>
                </div>
            </div>
        </div>
        <div class="filter-item">
            <!-- Sección: Datos del Registro -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos">
                        <i class="fa-solid fa-clipboard-user"></i> Datos del Registro
                    </h3>
                    <p class="section-description">Usuario responsable de la venta, turno y sede de registro.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-user"></i> Usuario
                            </label>
                            @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                                <select class="filter-dropdown" name="fkusers" required>
                                    <option value="">Seleccione un Usuario</option>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="fkusers" value="{{ auth()->user()->id }}">
                                <input class="filter-dropdown" type="text" value="{{ auth()->user()->name }}" disabled>
                            @endif
                        </div>
                        @if(auth()->user()->is(\App\Models\User::ROL_ADMIN) || auth()->user()->is(App\Models\User::ROL_EMPLEADO) )
        
                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-clock"></i> Turno del Trainer
                            </label>
                            <select class="filter-dropdown" name="venta_entre">
                                <option value="">Ninguno</option>
                                <option value="Trainer Mañana">Trainer Mañana</option>
                                <option value="Trainer Tarde">Trainer Tarde</option>
                            </select>
                        </div>
                        @endif
                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-location-dot"></i> Sede
                            </label>
                            @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                                <select class="filter-dropdown" name="fksede" required>
                                    <option value="">Seleccione Lugar de Registro</option>
                                    @foreach ($sedes as $sede)
                                        <option value="{{ $sede->id_sede }}">{{ $sede->sede_nombre }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input class="filter-dropdown" type="text" value="{{ $producto->sede?->sede_nombre }}" readonly>
                                <input type="hidden" name="fksede" value="{{ $producto->fksede }}">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




<div class="form-actions-enhanced">
    <a href="{{route('producto.index')}}" class="btn btn-secondary">
        <i class="icono-volver"></i> Lista de Productos
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-cart-plus"></i>
        Realizar Venta
    </button>
</div>



</form>

<script>
    const estadoPagoSelect = document.getElementById('estado_venta');
    const camposIncompleto = document.getElementById('campos-incompleto');
    const campoIncrementado = document.getElementById('venta_incrementado');
    const totalInput = document.getElementById('total');
    const cantidadInput = document.querySelector('input[name="cantidad"]');
    const precioUnitario = {{ $producto->prod_precio }};

    function calcularTotal() {
        const cantidad = parseFloat(cantidadInput.value) || 0;
        const incremento = parseFloat(campoIncrementado?.value) || 0;

        const total = (cantidad * precioUnitario) + incremento;
        totalInput.value = total.toFixed(2);
    }
    cantidadInput.addEventListener('input', calcularTotal);
    campoIncrementado.addEventListener('input', calcularTotal);
    function closeModal() {
        document.getElementById('ventaModal').style.display = 'none';
    }

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

@section('scripts')
    <script src="{{ asset('js/buscar_alumnos.js') }}"></script>
@endsection

