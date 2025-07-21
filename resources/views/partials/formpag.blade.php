@csrf
@php
    use App\Models\Membresias;use App\Models\User;use Illuminate\Support\Facades\Auth;
@endphp

<div class="filter-row">
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-id-card"></i>
                    Información de Membresía
                </h3>
                <p class="section-description">Seleccione el tipo de membresía para el cliente</p>
            </div>
            <div class="section-content">
                <div class="filter-item">
                    <label for="fkmem" class="filter-label enhanced-label">
                        <i class="fa-solid fa-tags"></i>
                        Membresía
                    </label>
                    <select name="fkmem" id="fkmem" class="filter-dropdown enhanced-select" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach($membresia as $mem)
                            <option value="{{ $mem->id_mem }}"
                                    data-duracion="{{ $mem->mem_durac }}"
                                    data-costo="{{ $mem->mem_cost }}"
                                    data-tipo="{{ $mem->tipo }}"
                                    data-categoria="{{ $mem->categoria_m->nombre_m }}"
                                    data-limit="{{ $mem->mem_limit }}"
                                {{ old('fkmem', $pago->fkmem) == $mem->id_mem ? 'selected' : ''}}>
                                🏷️ {{ $mem->categoria_m->nombre_m }} {{ $mem->mem_nomb }} ({{ $mem->tipo }})
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('fkmem'))
                        <span class="error-message">{{ $errors->first('fkmem') }}</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="filter-item" id="alumnoFields"
        style="display:{{ old('fkmem') && optional(Membresias::with('categoria_m')->find(old('fkmem')))->categoria_m->nombre_m !== 'Rutina' ? 'block' : 'none' }};">

        <div  class="form-section" >
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-user-graduate"></i>
                    Datos del Alumno
                </h3>
                <p class="section-description">Información del estudiante que adquiere la membresía</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="alum_codigo" class="filter-label enhanced-label">
                            <i class="fa-solid fa-hashtag"></i>
                            Código del Alumno
                        </label>
                        <input type="hidden" id="fkalumno" name="fkalum" value="{{ old('fkalum', $pago->fkalum ?? '') }}">
                        <input type="text" id="alum_codigo" name="alum_codigo"
                               value="{{ old('alum_codigo', $pago->alumno->alum_codigo ?? $codigoAlumno) }}"
                               class="filter-dropdown enhanced-input" placeholder="Ingrese código del alumno">
                    </div>
                    <div class="filter-item filter-button-container">
                        <button type="button" id="buscarAlumno" class="action-btn action-btn-primary">
                            <i class="fa-solid fa-search"></i> Buscar
                        </button>
                    </div>
                </div>

                <div class="filter-item">
                    <label for="nombre_alumno" class="filter-label enhanced-label">
                        <i class="fa-solid fa-user"></i>
                        Nombre Completo
                    </label>
                    <input type="text" id="nombre_alumno" name="nombre_alumno"
                           value="{{ old('nombre_alumno', $pago->alumno->nombre_completo ?? 'Sin Nombre Completo' ) }}"
                           class="filter-dropdown enhanced-input" readonly>
                </div>
                @if($errors->has('fkalum'))
                    <span class="error-message">{{ $errors->first('fkalum') }}</span>
                @endif
            </div>
        </div>

    </div>


</div>


<div class="filter-row">
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-calendar-days"></i>
                    Fechas de Membresía
                </h3>
                <p class="section-description">Período de vigencia de la membresía</p>
            </div>
            <div class="section-content">
                @if(Auth::user()->is(User::ROL_EMPLEADO) && request()->is('*/editar'))
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="pag_inicio" class="filter-label enhanced-label">
                                <i class="fa-solid fa-calendar-plus"></i>
                                Fecha de Inicio
                            </label>
                            <input type="date" id="pag_inicio" value="{{ $pago->pag_inicio }}"
                                   class="filter-dropdown enhanced-input" disabled>
                        </div>
                        <div class="filter-item">
                            <label for="pag_fin" class="filter-label enhanced-label">
                                <i class="fa-solid fa-calendar-minus"></i>
                                Fecha Fin
                            </label>
                            <input type="date" id="pag_fin" value="{{ $pago->pag_fin }}"
                                   class="filter-dropdown enhanced-input" disabled>
                        </div>

                    </div>
                @else
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="pag_inicio" class="filter-label enhanced-label">
                                <i class="fa-solid fa-calendar-plus"></i>
                                Fecha de Inicio
                            </label>
                            <input type="date" name="pag_inicio" id="pag_inicio"
                                   value="{{ old('pag_inicio', $pago->pag_inicio ?? '') }}"
                                   class="filter-dropdown enhanced-input">
                            @if ($errors->has('pag_inicio'))
                                <span class="error-message">{{ $errors->first('pag_inicio') }}</span>
                            @endif
                        </div>
                        <div class="filter-item">
                            <label for="pag_fin" class="filter-label enhanced-label">
                                <i class="fa-solid fa-calendar-minus"></i>
                                Fecha Fin
                            </label>
                            <input type="date" name="pag_fin" id="pag_fin"
                                   value="{{ old('pag_fin', $pago->pag_fin ?? '') }}"
                                   class="filter-dropdown enhanced-input" disabled>
                        </div>
                    </div>
                    @if(auth()->user()->is(\App\Models\User::ROL_ADMIN) && request()->is('*/editar'))
                        <div class="filter-item">
                            <label for="pag_update" class="filter-label enhanced-label">
                                <i class="fa-solid fa-calendar-check"></i>
                                Editar Fecha de Fin
                            </label>
                            <input type="date" name="pag_update" id="pag_update"
                                   class="filter-dropdown enhanced-input"
                                   value="{{ old('pag_fin', $pago->pag_fin ?? '') }}">
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    Información de Pago
                </h3>
                <p class="section-description">Detalles del pago y método de pago</p>
            </div>
            <div class="section-content">
                @if(Auth::user()&& request()->is('*/editar'))
                    <div class="filter-item">
                        <div class="info-highlight">
                            <i class="fa-solid fa-info-circle"></i>
                            <span id="monto-cancelado" data-monto="{{ $montoActual }}" >Monto actual Cancelado: S/ {{ number_format($montoActual, 2) }}</span>
                        </div>
                        @if($pago->estado_pago === 'completo')
                            <div class="info-highlight">
                                <i class="fa-solid fa-info-circle"></i>
                                <span id="monto-restante">
                            </span>
                            </div>
                        @endif

                    </div>
                @endif

                <div class="filter-row">
                    <div class="filter-item">
                        <label for="fkmetodo" class="filter-label enhanced-label">
                            <i class="fa-solid fa-credit-card"></i>
                            Método de Pago
                        </label>
                        <select name="fkmetodo" id="fkmetodo" class="filter-dropdown enhanced-select" required>
                            <option value="">Seleccione el método de pago</option>
                            @foreach($metodo as $metod)
                                <option value="{{ $metod->id_metod }}"
                                    {{ old('fkmetodo', $pago->fkmetodo) == $metod->id_metod ? 'selected' : '' }}>
                                    💳 {{ $metod->tipo_pago }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="pago" class="filter-label enhanced-label">
                            <i class="fa-solid fa-dollar-sign"></i>
                            Pago Total
                        </label>
                        <input type="number" name="pago" id="pago"
                               value="{{ old('pago', $pago->pago ?? '') }}" step="0.01"
                               class="filter-dropdown enhanced-input" readonly>
                    </div>
                </div>

                <div class="filter-item">
                    <label for="estado_pago" class="filter-label enhanced-label">
                        <i class="fa-solid fa-check-circle"></i>
                        Estado del Pago
                    </label>
                    <select name="estado_pago" id="estado_pago" class="filter-dropdown enhanced-select" required>
                        <option value="completo" {{ old('estado_pago', $pago->estado_pago ?? '') === 'completo' ? 'selected' : '' }}>
                            ✅ Completo
                        </option>
                        <option value="incompleto" {{ old('estado_pago', $pago->estado_pago ?? '') === 'incompleto' ? 'selected' : '' }}>
                            ⏳ Incompleto
                        </option>
                    </select>
                    @if($errors->has('estado_pago'))
                        <span class="error-message">{{ $errors->first('estado_pago') }}</span>
                    @endif
                </div>

                <!-- Subsección para Pago Incompleto -->
                <div id="campos-incompleto" class="payment-subsection"
                     style="display: {{ old('estado_pago', $pago->estado_pago ?? '') === 'incompleto' ? 'block' : 'none' }};">
                    <div class="subsection-header">
                        <h4 class="subsection-title">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                            Detalles de Pago Incompleto
                        </h4>
                    </div>
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="fecha_limite_pago" class="filter-label enhanced-label">
                                <i class="fa-solid fa-clock"></i>
                                Fecha Límite para Pagar
                            </label>
                            <input type="date" name="fecha_limite_pago" id="fecha_limite_pago"
                                   value="{{ old('fecha_limite_pago', $pago->fecha_limite_pago ? \Carbon\Carbon::parse($pago->fecha_limite_pago)->format('Y-m-d') : '') }}"
                                   class="filter-dropdown enhanced-input">
                            @if($errors->has('fecha_limite_pago'))
                                <span class="error-message">{{ $errors->first('fecha_limite_pago') }}</span>
                            @endif
                        </div>
                        <div class="filter-item">
                            <label for="monto_pagado" class="filter-label enhanced-label">
                                <i class="fa-solid fa-money-check"></i>
                                Monto Pagado
                            </label>
                            <input type="number" name="monto_pagado" id="monto_pagado"
                                   value="{{ old('monto_pagado', $pago->monto_pagado ?? '') }}"
                                   step="0.01" class="filter-dropdown enhanced-input">
                            @if($errors->has('monto_pagado'))
                                <span class="error-message">{{ $errors->first('monto_pagado') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="filter-item">
                        <label for="saldo_pendiente" class="filter-label enhanced-label">
                            <i class="fa-solid fa-balance-scale"></i>
                            Saldo Pendiente
                        </label>
                        <input type="number" name="saldo_pendiente" id="saldo_pendiente"
                               value="{{ old('saldo_pendiente', $pago->saldo_pendiente ?? '') }}"
                               step="0.01" class="filter-dropdown enhanced-input" readonly>
                        @if($errors->has('saldo_pendiente'))
                            <span class="error-message">{{ $errors->first('saldo_pendiente') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="filter-row">
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-user-tie"></i>
                    Entrenador Personal
                </h3>
                <p class="section-description">Asignación de entrenador personal (opcional)</p>
            </div>
            <div class="section-content">
                <div class="filter-item">
                    <label for="pag_entre" class="filter-label enhanced-label">
                        <i class="fa-solid fa-dumbbell"></i>
                        Pago Trainer
                    </label>
                    <select name="pag_entre" id="pag_entre" class="filter-dropdown enhanced-select">
                        <option value="">🚫 Ninguno</option>
                        <option value="Trainer Mañana" {{ old('pag_entre', $pago->pag_entre ?? '') === 'Trainer Mañana' ? 'selected' : '' }}>
                            🌅 Trainer Mañana
                        </option>
                        <option value="Trainer Tarde" {{ old('pag_entre', $pago->pag_entre ?? '') === 'Trainer Tarde' ? 'selected' : '' }}>
                            🌆 Trainer Tarde
                        </option>
                    </select>
                    @if($errors->has('pag_entre'))
                        <span class="error-message">{{ $errors->first('pag_entre') }}</span>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <div class="filter-item">
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos">
                        <i class="fa-solid fa-users-cog"></i>
                        Configuración Administrativa
                    </h3>
                    <p class="section-description">Configuración avanzada solo para administradores</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
         @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                    
                        <div class="filter-item">
                            <label for="fkuser" class="filter-label enhanced-label">
                                <i class="fa-solid fa-user-shield"></i>
                                Usuario de Registro
                            </label>
                            <select name="fkuser" id="fkuser" class="filter-dropdown enhanced-select">
                                <option value="">Seleccione un usuario</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id}}" {{ old('fkuser', $pago->fkuser) == $u->id ? 'selected' : '' }}>
                                        👤 {{ $u->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('fkuser'))
                                <span class="error-message">{{ $errors->first('fkuser') }}</span>
                            @endif
                        </div>
        @endif
                        
                        <input type="hidden" name="fkuser" value="{{ auth()->user()->id }}">

                        <div class="filter-item">
                            <label for="fksede" class="filter-label enhanced-label">
                                <i class="fa-solid fa-building"></i>
                                Sede
                            </label>
                            
                            <select name="fksede" id="fksede" class="filter-dropdown enhanced-select">
                                <option value="">Seleccione una sede</option>
                                @foreach($sede as $s)
                                    <option value="{{ $s->id_sede }}" {{ old('fksede', $pago->fksede ?? '') == $s->id_sede ? 'selected' : '' }}>
                                        🏢 {{ $s->sede_nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('fksede'))
                                <span class="error-message">{{ $errors->first('fksede') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        
            {{-- <input type="hidden" name="fksede" value="{{ auth()->user()->fksede }}"> --}}
        
    </div>
</div>

    <!-- Botones de Acción -->
    <div class="form-actions-enhanced">
        <a href="{{route('pagos.completos')}}" class="btn btn-cancelar">
            <i class="fa-solid fa-times"></i>
            Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save"></i> {{$btnText}}
        </button>
    </div>


<script>
        document.addEventListener('DOMContentLoaded', function () {
            const membresiaSelect = document.getElementById('fkmem');
            const alumnoFields = document.getElementById('alumnoFields');
            const fechaInicioInput = document.getElementById('pag_inicio');
            const fechaFinInput = document.getElementById('pag_fin');
            const fechaUpdateInput = document.getElementById('pag_update');
            const pagoTotalInput = document.getElementById('pago');
            const montoPagadoInput = document.getElementById('monto_pagado');
            const saldoPendienteInput = document.getElementById('saldo_pendiente');
            const estadoPagoSelect = document.getElementById('estado_pago');
            const camposIncompleto = document.getElementById('campos-incompleto');
            const fechaLimitePagoInput = document.getElementById('fecha_limite_pago');
            const montoCanceladoSpan = document.getElementById('monto-cancelado');
            const montoRestanteSpan = document.getElementById('monto-restante');
            const userRole = "{{ Auth::user()->rol }}";

            function updateMontoRestante() {
                if (!montoCanceladoSpan || !montoRestanteSpan || !pagoTotalInput) {
                    console.error("Elementos no encontrados para calcular monto restante");
                    return;
                }

                try {
                    const montoCancelado = parseFloat(montoCanceladoSpan.getAttribute('data-monto')) || 0;
                    const pagoTotal = parseFloat(pagoTotalInput.value) || 0;

                    // console.log("Monto cancelado:", montoCancelado, "Pago total:", pagoTotal); // Para depuración

                    const montoRestante = Math.max(0, pagoTotal - montoCancelado);
                    montoRestanteSpan.textContent = `Monto Restante a cancelar: S/ ${montoRestante.toFixed(2)}`;

                    // Actualizar también el saldo pendiente si existe
                    if (saldoPendienteInput) {
                        const montoPagado = parseFloat(montoPagadoInput.value) || 0;
                        saldoPendienteInput.value = (montoRestante - montoPagado).toFixed(2);
                    }
                } catch (error) {
                    console.error("Error en updateMontoRestante:", error);
                }
            }

            // Función para actualizar el formulario
            function updateForm() {
                const path = window.location.pathname;
                const isEditView = path.includes('/editar');

                const isAdminOrEmpleado = userRole === 'empleado'
                const isAdminOrAdmin = userRole === 'admin' 
                
                // Habilitar campos si estamos en vista de edición
                if (isEditView && isAdminOrEmpleado) {
                    fechaFinInput.disabled = true;
                    fechaInicioInput.disabled = true;
                }

                if (isEditView && isAdminOrAdmin) {
                    fechaFinInput.disabled = true;
                    fechaInicioInput.disabled = false;
                }

                const selectedOption = membresiaSelect.options[membresiaSelect.selectedIndex];
                if (!selectedOption || selectedOption.value === '') {
                    alumnoFields.style.display = 'none';
                    return;
                }

                const categoria = selectedOption.getAttribute('data-categoria');
                const mostrarAlumno = categoria !== 'Rutina';

                alumnoFields.style.display = mostrarAlumno ? 'block' : 'none';
                pagoTotalInput.value = selectedOption.getAttribute('data-costo') || '0';

                // Solo aplicar lógica de categoría si NO estamos en modo edición
                if (!isEditView) {
                    if (categoria === 'Registro') {
                        fechaFinInput.disabled = false;
                        fechaInicioInput.disabled = false;
                    } else {
                        const duracion = parseInt(selectedOption.getAttribute('data-duracion')) || 0;
                        const limitDate = selectedOption.getAttribute('data-limit');

                        if (duracion > 0) {
                            fechaFinInput.disabled = true;
                            if (fechaInicioInput.value) {
                                const fechaInicio = new Date(fechaInicioInput.value);
                                const fechaFin = new Date(fechaInicio);
                                fechaFin.setDate(fechaFin.getDate() + duracion);
                                fechaFinInput.value = fechaFin.toISOString().split('T')[0];
                            }
                        } else if (limitDate) {
                            fechaFinInput.disabled = true;
                            fechaFinInput.value = limitDate;
                        } else {
                            fechaFinInput.disabled = false;
                        }
                    }
                }

                if (fechaUpdateInput) {
                    fechaUpdateInput.value = fechaFinInput.value;
                }

                updateMontoRestante();
            }



            // Función para calcular el saldo pendiente
            function updateSaldoPendiente() {
                const selectedOption = membresiaSelect.options[membresiaSelect.selectedIndex];
                if (!selectedOption || selectedOption.value === '') return;

                // Obtener el costo total de la membresía
                const costoTotal = parseFloat(selectedOption.getAttribute('data-costo')) || 0;

                // Obtener el monto pagado (asegurarse de que sea un número válido)
                const montoPagado = parseFloat(montoPagadoInput.value) || 0;

                // Calcular el saldo pendiente
                const saldoPendiente = Math.max(0, costoTotal - montoPagado);

                // Actualizar el campo de saldo pendiente
                saldoPendienteInput.value = saldoPendiente.toFixed(2);
            }

            // Función para mostrar/ocultar y limpiar campos incompletos
            function toggleCamposIncompleto() {
                if (estadoPagoSelect.value === 'incompleto') {
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
            //if conficion de actulizar input fin

            if (fechaUpdateInput && fechaFinInput) {
                fechaUpdateInput.addEventListener('change', function () {
                    fechaFinInput.value = this.value;
                });
            }



            // Event listeners
            membresiaSelect.addEventListener('change', updateForm);
            fechaInicioInput.addEventListener('change', updateForm);
            membresiaSelect.addEventListener('change', updateSaldoPendiente);
            montoPagadoInput.addEventListener('input', updateSaldoPendiente);
            estadoPagoSelect.addEventListener('change', toggleCamposIncompleto);

            // Inicializar al cargar la página
            updateForm();
            updateSaldoPendiente();
            toggleCamposIncompleto();
            updateMontoRestante();
        });
    </script>
<script src="{{ asset('js/buscar_alumnos.js') }}"></script>
<script>
    const $codigoAlumno = @json($codigoAlumno);

    if ($codigoAlumno) {
        buscarAlumno($codigoAlumno);
    }

</script>
