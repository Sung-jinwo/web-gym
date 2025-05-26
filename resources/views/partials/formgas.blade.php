@csrf


<div class="filter-row">
    <div class="filter-item">
        <!-- Sección: Detalles del Gasto -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos"><i class="fa-solid fa-money-check-dollar"></i> Detalles del Gasto</h3>
                <p class="section-description">Completa la categoría, descripción y monto del gasto realizado.</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="gast_categoria" class="filter-label"><i class="fa-solid fa-tags"></i> Categoría de Gasto</label>
                        <select id="gast_categoria" name="gast_categoria" class="filter-dropdown" required>
                            <option value="">Seleccione una categoría</option>
                            <option value="Electricidad" {{ old('gast_categoria', $gastos->gast_categoria) == 'Electricidad' ? 'selected' : '' }}>Electricidad</option>
                            <option value="Agua" {{ old('gast_categoria', $gastos->gast_categoria) == 'Agua' ? 'selected' : '' }}>Agua</option>
                            <option value="Internet" {{ old('gast_categoria', $gastos->gast_categoria) == 'Internet' ? 'selected' : '' }}>Internet</option>
                            <option value="Limpieza" {{ old('gast_categoria', $gastos->gast_categoria) == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                            <option value="Sueldos" {{ old('gast_categoria', $gastos->gast_categoria) == 'Sueldos' ? 'selected' : '' }}>Sueldos</option>
                            <option value="Alimentos" {{ old('gast_categoria', $gastos->gast_categoria) == 'Alimentos y suplementos' ? 'selected' : '' }}>Alimentos y suplementos</option>
                            <option value="Utensilios" {{ old('gast_categoria', $gastos->gast_categoria) == 'Utensilios deportivos' ? 'selected' : '' }}>Utensilios deportivos</option>
                            <option value="Mantenimiento de equipos" {{ old('gast_categoria', $gastos->gast_categoria) == 'Mantenimiento de equipos' ? 'selected' : '' }}>Mantenimiento de equipos</option>
                            <option value="Reparaciones generales" {{ old('gast_categoria', $gastos->gast_categoria) == 'Reparaciones generales' ? 'selected' : '' }}>Reparaciones generales</option>
                            <option value="Papelería y oficina" {{ old('gast_categoria', $gastos->gast_categoria) == 'Papelería y oficina' ? 'selected' : '' }}>Papelería y oficina</option>
                            <option value="Capacitación del personal" {{ old('gast_categoria', $gastos->gast_categoria) == 'Capacitación del personal' ? 'selected' : '' }}>Capacitación del personal</option>
                            <option value="Otros" {{ old('gast_categoria', $gastos->gast_categoria) == 'Otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                        @if($errors->has('gast_categoria'))
                            <span class="error-message">{{ $errors->first('gast_categoria') }}</span>
                        @endif
                    </div>
                    <div class="filter-item">
                        <label for="descripcion" class="filter-label"><i class="fa-solid fa-align-left"></i> Descripción</label>
                        <input type="text" id="descripcion" name="gast_descripcion"
                               class="filter-dropdown" placeholder="Ej: Describir el Gasto"
                               value="{{ old('gast_descripcion', $gastos->gast_descripcion) }}">
                        @if($errors->has('gast_descripcion'))
                            <span class="error-message">{{ $errors->first('gast_descripcion') }}</span>
                        @endif
                    </div>
                </div>

                <div class="filter-row">
                    <div class="filter-item">
                        <label for="monto" class="filter-label"><i class="fa-solid fa-dollar-sign"></i> Monto</label>
                        <input type="text" id="monto" name="gast_monto"
                               class="filter-dropdown" placeholder="Ej: 120.50"
                               value="{{ old('gast_monto', $gastos->gast_monto) }}">
                        @if($errors->has('gast_monto'))
                            <span class="error-message">{{ $errors->first('gast_monto') }}</span>
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
                <h3 class="Sub_titulos"><i class="fa-solid fa-user-pen"></i> Datos del Registro</h3>
                <p class="section-description">Información del responsable que registró el gasto y la sede correspondiente.</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="fkuser" class="filter-label"><i class="fa-solid fa-user"></i> Usuario Responsable</label>
                        @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                            <select name="fkuser" id="fkuser" class="filter-dropdown" required>
                                <option value="">Seleccione un Usuario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('fkuser', $gastos->fkuser) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" name="fkuser" value="{{ auth()->user()->id }}">
                            <input type="text" class="filter-dropdown" value="{{ auth()->user()->name }}" disabled>
                        @endif
                        @if($errors->has('fkuser'))
                            <span class="error-message">{{ $errors->first('fkuser') }}</span>
                        @endif
                    </div>

                    <div class="filter-item">
                        <label for="fksede" class="filter-label"><i class="fa-solid fa-location-dot"></i> Sede</label>
                        @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                            <select name="fksede" id="fksede" class="filter-dropdown" required>
                                <option value="">Seleccione Lugar de Registro</option>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id_sede }}" {{ old('fksede', $gastos->fksede) == $sede->id_sede ? 'selected' : '' }}>
                                        {{ $sede->sede_nombre }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" name="fksede" value="{{ auth()->user()->fksede }}">
                            <input type="text" class="filter-dropdown" value="{{ auth()->user()->sede->sede_nombre }}" disabled>
                        @endif
                        @if($errors->has('fksede'))
                            <span class="error-message">{{ $errors->first('fksede') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<div class="form-actions-enhanced">
    <a href="{{route('gasto.index')}}" class="btn btn-secondary">
        <i class="icono-volver"></i> Lista de Gastos
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="icono-guardar"></i>
        {{$btnText}}
    </button>
</div>
