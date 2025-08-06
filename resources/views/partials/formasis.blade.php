@csrf
<input type="hidden" id="fkalumno" name="fkalum" value="{{ old('fkalum',  $asistencia->fkalum ?? '')}}">

<div class="filter-row">
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-hashtag"></i>
                    Código de Identificación
                </h3>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="fkalum" class="filter-label">
                            <i class="fa-solid fa-hashtag"></i> Código del Alumno
                        </label>
                        <input type="text" id="alum_codigo" name="alum_codigo"
                               value="{{ old('alum_codigo', $asistencia->alumno->alum_codigo ?? $codigoAlumno)}}"
                               class="filter-dropdown "
                               placeholder="0000"
                               pattern="\d{4}"
                               title="El código debe contener exactamente 4 dígitos"
                               maxlength="4"
                               required>
                        @if($errors->has('fkalum'))
                            <span class="error-message">{{ $errors->first('fkalum') }}</span>
                        @endif

                    </div>
                    <div class="filter-item filter-button-container">
                        <button type="button" id="buscarAlumno" class="action-btn action-btn-primary">
                            <i class="fa-solid fa-search"></i> Buscar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-user"></i>
                    Información del Alumno
                </h3>
            </div>
            <div class="section-content">
                <label for="nombre_alumno" class="filter-label ">
                    <i class="icono-alumno"></i> Nombre Completo del Alumno
                </label>
                <input type="text" id="nombre_alumno" name="nombre_alumno"
                       value="{{old('nombre_alumno', $asistencia->alumno->nombre_completo ?? 'No tiene Nombre Completo')}}"
                       class="filter-dropdown "
                       readonly>
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
                    Usuario de Registro
                </h3>
            </div>
            <div class="section-content">
                @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                    <label for="fkuser" class="filter-label">
                        <i class="icono-usuario"></i> Usuario de Registro
                    </label>
                    <select name="fkuser" id="fkuser" class="filter-dropdown">
                        <option value="">Seleccione un usuario</option>
                        @foreach($user as $u)
                            <option value="{{ $u->id}}" {{ old('fkuser', $asistencia->fkuser) == $u->id ? 'selected' : '' }}>
                                {{ $u->name}}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('fkuser'))
                        <span class="error-message">{{ $errors->first('fkuser') }}</span>
                    @endif
                @else
                    <label class="filter-label">
                        <i class="icono-usuario"></i> Usuario:
                    </label>
                    <input type="hidden" name="fkuser" value="{{ auth()->user()->id }}">
                    <input type="text" value="{{ auth()->user()->name }}"
                           class="filter-dropdown"
                           disabled>
                @endif
            </div>
        </div>
    </div>

    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-location-dot"></i>
                    Lugar de Registro
                </h3>
            </div>
            <div class="section-content">
                <label for="fksede" class="filter-label">
                    <i class="fa-solid fa-location-dot"></i> Lugar de Registro
                </label>
                @if (auth()->user()->is(\App\Models\User::ROL_ADMIN|| auth()->user()->is(App\Models\User::ROL_ASISTENCIA) || auth()->user()->is(App\Models\User::ROL_VENTAS)))
                    <select name="fksede" id="fksede" class="filter-dropdown">
                        <option value="">Seleccione una sede</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id_sede }}" {{ old('fksede', $asistencia->fksede) == $sede->id_sede ? 'selected' : '' }}>
                                {{ $sede->sede_nombre }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('fksede'))
                        <span class="error-message">{{ $errors->first('fksede') }}</span>
                    @endif
                @else
                    <input type="hidden" name="fksede" value="{{ auth()->user()->fksede }}">
                    <select class="filter-dropdown" disabled>
                        <option>
                            {{ auth()->user()->sede->sede_nombre}}
                        </option>
                    </select>
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
                    <i class="fa-solid fa-calendar-check"></i>
                    Fecha y Hora de Asistencia
                </h3>
            </div>
            <div class="section-content">
                <label for="visi_fecha" class="filter-label">
                    <i class="fa-solid fa-calendar"></i> Fecha de Asistencia
                </label>
                <div class="time-display-container">
                    <span id="reloj" class="time-display enhanced-input"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-actions-enhanced">
    <a href="{{route('asistencia.index')}}" class="btn btn-secondary">
        <i class="icono-volver"></i> Lista de asistencia
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="icono-guardar"></i> {{$btnText}}
    </button>
</div>

<script>
    function actualizarHora() {
        let ahora = new Date();
        let fecha = ahora.toLocaleDateString('es-ES');
        let hora = ahora.toLocaleTimeString('es-ES');
        document.getElementById('reloj').innerText = fecha + ' ' + hora;
    }

    setInterval(actualizarHora, 1000); // Actualiza cada segundo
    actualizarHora(); // Ejecuta la función al cargar la página
</script>
<script src="{{ asset('js/buscar_alumnos.js') }}"></script>
<script>
    const $codigoAlumno = @json($codigoAlumno);

    if ($codigoAlumno) {
        buscarAlumno($codigoAlumno);
    }
</script>

