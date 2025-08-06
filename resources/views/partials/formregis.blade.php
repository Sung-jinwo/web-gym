@csrf
<!-- Información Personal del Alumno -->
<div class="filter-row">
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-user-graduate"></i>
                    Información Personal del Alumno
                </h3>
                <p class="section-description">Ingrese el nombre, apellido y otros datos personales del alumno.</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="nombre" class="filter-label">
                            <i class="fa-solid fa-user"></i> Nombres
                        </label>
                        <input type="text" id="nombre" name="alum_nombre" placeholder="Ingrese nombre" value="{{ old('alum_nombre', $registros->alum_nombre) }}" class="filter-dropdown">
                        @if($errors->has('alum_nombre'))
                            <span class="error-message">{{ $errors->first('alum_nombre') }}</span>
                        @endif
                    </div>

                    <div class="filter-item">
                        <label for="apellido" class="filter-label">
                            <i class="fa-solid fa-user"></i> Apellidos
                        </label>
                        <input type="text" id="apellido" name="alum_apellido"  placeholder="Ingrese apellido" value="{{ old('alum_apellido', $registros->alum_apellido) }}" class="filter-dropdown">
                        @if($errors->has('alum_apellido'))
                            <span class="error-message">{{ $errors->first('alum_apellido') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-address-book"></i>
                    Datos de Contacto
                </h3>
                <p class="section-description">Información de contacto y sexo del alumno.</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="fksexo" class="filter-label">
                            <i class="fa-solid fa-venus-mars"></i> Sexo
                        </label>
                        <select name="fksexo" id="fksexo" class="filter-dropdown">
                            <option value="">Seleccione el sexo</option>
                            <option value="1" {{ old('fksexo', $registros->fksexo) == '1' ? 'selected' : '' }}>Masculino</option>
                            <option value="2" {{ old('fksexo', $registros->fksexo) == '2' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @if($errors->has('fksexo'))
                            <span class="error-message">{{ $errors->first('fksexo') }}</span>
                        @endif
                    </div>

                    <div class="filter-item">
                        <label for="alum_telefo" class="filter-label">
                            <i class="fa-solid fa-phone"></i> Teléfono
                        </label>
                        <input type="tel" id="alum_telefo" name="alum_telefo" placeholder="Ingrese telefono" value="{{ old('alum_telefo', $registros->alum_telefo) }}" class="filter-dropdown" maxlength="9" required pattern="\d{9}">
                        @if($errors->has('alum_telefo'))
                            <span class="error-message">{{ $errors->first('alum_telefo') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Datos de Contacto -->

<!-- Información Adicional -->
<div class="form-section">
    <div class="section-header">
        <h3 class="Sub_titulos">
            <i class="fa-solid fa-calendar-days"></i>
            Información Adicional
        </h3>
        <p class="section-description">Fecha de nacimiento y lugar de registro del alumno.</p>
    </div>
    <div class="section-content">
        <div class="filter-row">
            <div class="filter-item">
                <label for="alum_eda" class="filter-label">
                    <i class="fa-solid fa-calendar"></i> Fecha de nacimiento
                </label>
                <input type="date" id="alum_eda" name="fecha_nac" value="{{ old('fecha_nac', $registros->fecha_nac) }}" class="filter-dropdown">
                @if($errors->has('fecha_nac'))
                    <span class="error-message">{{ $errors->first('fecha_nac') }}</span>
                @endif
            </div>

            <div class="filter-item">
                <label for="fksede" class="filter-label">
                    <i class="fa-solid fa-location-dot"></i> Lugar de Registro
                </label>
                @if (auth()->user()->is(\App\Models\User::ROL_ADMIN)|| auth()->user()->is(App\Models\User::ROL_VENTAS))
                    <select name="fksede" id="fksede" class="filter-dropdown">
                        <option value="">Seleccione Lugar de Registro</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id_sede }}" {{ old('fksede', $registros->fksede) == $sede->id_sede ? 'selected' : '' }}>
                                {{ $sede->sede_nombre }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" name="fksede" value="{{ auth()->user()->fksede }}">
                    <select class="filter-dropdown" disabled>
                        <option>
                            {{ auth()->user()->sede->sede_nombre }}
                        </option>
                    </select>
                @endif
                @if($errors->has('fksede'))
                    <span class="error-message">{{ $errors->first('fksede') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Botón de Acción -->
<div class="form-actions-enhanced">
    <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $btnText }}
    </button>
</div>
