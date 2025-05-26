@csrf
<input type="hidden" name="fkalumno" value="{{ $alumno->id_alumno }}">

<div class="form-section">
    <div class="section-header">
        <h3 class="Sub_titulos"><i class="fa-solid fa-users"></i> Datos del Apoderado</h3>
        <p class="section-description">Completa los datos del apoderado relacionado con el alumno.</p>
        <br>
        <label class="Sub_titulos">
            <i class="fa-solid fa-user-graduate"></i> Alumno: <strong>{{ $alumno->nombre_completo }}</strong>
        </label>
    </div>

    <div class="section-content">
        <div class="filter-row">
            <div class="filter-item">
                <label for="padre_nombre" class="filter-label">
                    <i class="fa-solid fa-user"></i> Nombre
                </label>
                <input type="text" name="padre_nombre" id="padre_nombre" class="padres-unique-input"
                       placeholder="Ingrese el nombre del apoderado"
                       value="{{ old('padre_nombre', $padre->padre_nombre ?? '') }}" required>
                @if ($errors->has('padre_nombre'))
                    <span class="error-message">{{ $errors->first('padre_nombre') }}</span>
                @endif
            </div>

            <div class="filter-item">
                <label for="padre_apellido" class="filter-label">
                    <i class="fa-solid fa-user-tag"></i> Apellido
                </label>
                <input type="text" name="padre_apellido" id="padre_apellido" class="padres-unique-input"
                       placeholder="Ingrese el apellido del apoderado"
                       value="{{ old('padre_apellido', $padre->padre_apellido ?? '') }}" required>
                @if ($errors->has('padre_apellido'))
                    <span class="error-message">{{ $errors->first('padre_apellido') }}</span>
                @endif
            </div>

            <div class="filter-item">
                <label for="padre_telefono" class="filter-label">
                    <i class="fa-solid fa-phone"></i> Teléfono
                </label>
                <input type="text" name="padre_telefono" id="padre_telefono" class="padres-unique-input"
                       placeholder="Ej. 987654321" maxlength="9"
                       value="{{ old('padre_telefono', $padre->padre_telefono ?? '') }}" required>
                @if ($errors->has('padre_telefono'))
                    <span class="error-message">{{ $errors->first('padre_telefono') }}</span>
                @endif
            </div>
        </div>

        <div class="filter-row">
            <div class="filter-item">
                <label for="padre_correo" class="filter-label">
                    <i class="fa-solid fa-envelope"></i> Correo Electrónico
                </label>
                <input type="email" name="padre_correo" id="padre_correo" class="padres-unique-input"
                       placeholder="correo@ejemplo.com"
                       value="{{ old('padre_correo', $padre->padre_correo ?? '') }}">
                @if ($errors->has('padre_correo'))
                    <span class="error-message">{{ $errors->first('padre_correo') }}</span>
                @endif
            </div>

            <div class="filter-item">
                <label for="fksexo" class="filter-label">
                    <i class="fa-solid fa-venus-mars"></i> Sexo
                </label>
                <select name="fksexo" id="fksexo" class="padres-unique-input" required>
                    <option value="">Seleccionar Sexo</option>
                    <option value="1" {{ old('fksexo', $padre->fksexo ?? '') == 1 ? 'selected' : '' }}>Masculino</option>
                    <option value="2" {{ old('fksexo', $padre->fksexo ?? '') == 2 ? 'selected' : '' }}>Femenino</option>
                </select>
                @if ($errors->has('fksexo'))
                    <span class="error-message">{{ $errors->first('fksexo') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="form-actions-enhanced">
    <a href="{{route('padres.index')}}" class="btn btn-secondary">
        <i class="icono-volver"></i> Lista de Gastos
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="icono-guardar"></i>
        {{$btnText}}
    </button>
</div>
