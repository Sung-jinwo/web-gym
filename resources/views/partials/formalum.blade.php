@csrf
<!-- Image Upload Section -->

<div class="filter-row">
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-camera"></i>
                    Fotografía del Alumno
                </h3>
                <p class="section-description">Sube una imagen del perfil del alumno</p>
            </div>
            <div class="section-content">
                <div class="filter-item image-upload-section">
                    <label class="filter-label enhanced-label">
                        <i class="fa-solid fa-image"></i>
                        Imagen Actual
                    </label>
                    <div class="image-preview-enhanced">
                        <div class="image-preview-wrapper" id="imagePreview">
                            @if($alumno->alum_img)
                                <img src="{{ asset($alumno->alum_img) }}" alt="Vista previa de {{ $alumno->alum_nombre }} {{ $alumno->alum_apellido }}" class="img">
                            @else
                                <div class="no-image-state">
                                    <i class="fa-solid fa-user-circle"></i>
                                    <span>No hay imagen disponible</span>
                                </div>
                            @endif
                        </div>
                        <div class="image-upload-controls">
                            <label For="customFile" class="upload-btn">
                                @if($alumno->alum_img)
                                    <i class="fa-solid fa-sync"></i>
                                    Cambiar imagen
                                @else
                                    <i class="fa-solid fa-cloud-upload-alt"></i>
                                    Seleccionar imagen
                                @endif
                            </label>
                            <input type="file" name="alum_img" id="customFile" class="file-input-hidden" data-current-file="{{ $alumno->alum_img }}" accept="image/*">
                            <span class="file-info" id="fileName">
                            @if($alumno->alum_img)
                                    {{ basename($alumno->alum_img) }}
                                @endif
                        </span>
                            @if($errors->has('alum_img'))
                                <span class="error-message">{{ $errors->first('alum_img') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-id-badge"></i>
                    Información de Identificación
                </h3>
                <p class="section-description">
                    Datos basicos del Alumno
                </p>
            </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="codigo" class="filter-label"> <i class="fa-solid fa-hashtag"></i> Codigo de Alumno</label>
                            <input type="text" id="alum_codigo" name="alum_codigo" placeholder="0000" value="{{ old('alum_codigo', $alumno->alum_codigo) }}" class="filter-dropdown code-input" maxlength="4" required>
                            @if($errors->has('alum_codigo'))
                                <span class="error-message">{{ $errors->first('alum_codigo') }}</span>
                            @endif
                        </div>
                        <div class="filter-item filter-button-container">
                            <button type="button" id="generate-code-btn" class="action-btn action-btn-primary">Generar Código</button>
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="nombre" class="filter-label"> <i class="icono-usuario"></i> Nombres</label>
                            <input type="text" id="nombre" name="alum_nombre" placeholder="Ingrese los nombres" value="{{ old('alum_nombre', $alumno->alum_nombre) }}" class="filter-dropdown">
                            @if($errors->has('alum_nombre'))
                                <span class="error-message">{{ $errors->first('alum_nombre') }}</span>
                            @endif
                        </div>
                        <div class="filter-item">
                            <label for="apellido" class="filter-label"> <i class="icono-nombre"></i> Apellidos</label>
                            <input type="text" id="apellido" name="alum_apellido" placeholder="Ingrese los apellidos" value="{{ old('alum_apellido', $alumno->alum_apellido) }}" class="filter-dropdown">
                            @if($errors->has('alum_apellido'))
                                <span class="error-message">{{ $errors->first('alum_apellido') }}</span>
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
                    <i class="fa-solid fa-file-alt"></i>
                    Documentación Legal
                </h3>
                <p class="section-description">Información de documentos de identidad</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="alum_documento" class="filter-label"> <i class="fa-solid fa-id-card"></i> Tipo de Documento</label>
                        <select name="alum_documento" id="alum_documento" class="filter-dropdown ">
                            <option value="">Seleccione EL Documento </option>
                            <option value="DNI" {{ old('alum_documento', $alumno->alum_documento) == 'DNI' ? 'selected' : '' }}>DNI</option>
                            <option value="DNIe" {{ old('alum_documento', $alumno->alum_documento) == 'DNIE' ? 'selected' : '' }}>DNIE</option>
                            <option value="PASS" {{ old('alum_documento', $alumno->alum_documento) == 'PASS' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="CE" {{ old('alum_documento', $alumno->alum_documento) == 'CE' ? 'selected' : '' }}>Carnet de Extranjería</option>
                        </select>
                        @if($errors->has('alum_documento'))
                            <span class="error-message">{{ $errors->first('alum_documento') }}</span>
                        @endif
                    </div>

                    <div class="filter-item">
                        <label for="numero" class="filter-label"> <i class="fa-solid fa-credit-card" ></i> Numero de Documento</label>
                        <input type="text" id="numero" name="alum_numDoc" placeholder="Ingrese el número" value="{{ old('alum_numDoc', $alumno->alum_numDoc) }}" class="filter-dropdown">
                        @if($errors->has('alum_numDoc'))
                            <span class="error-message">{{ $errors->first('alum_numDoc') }}</span>
                        @endif
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="alum_direccion" class="filter-label"><i class="fa-solid fa-map-marker-alt"></i>Dirección
                        </label>
                        <input type="text" id="alum_direccion" name="alum_direccion"  placeholder="Ingrese la dirección completa" value="{{ old('alum_direccion', $alumno->alum_direccion) }}" class="filter-dropdown">
                        @if($errors->has('alum_direccion'))
                            <span class="error-message">{{ $errors->first('alum_direccion') }}</span>
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
                    <i class="fa-solid fa-user-circle"></i>
                    Información Personal
                </h3>
                <p class="section-description">Datos personales y de contacto</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="fksexo" class="filter-label"><i class="fa-solid fa-venus-mars"></i> Sexo</label>
                        <select name="fksexo" id="fksexo" class="filter-dropdown">
                            <option value="">Seleccione el sexo</option>
                            <option value="1" {{ old('fksexo', $alumno->fksexo) == '1' ? 'selected' : '' }}>Masculino</option>
                            <option value="2" {{ old('fksexo', $alumno->fksexo) == '2' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @if($errors->has('fksexo'))
                            <span class="error-message">{{ $errors->first('fksexo') }}</span>
                        @endif
                    </div>

                    <div class="filter-item">
                        <label for="alum_eda" class="filter-label">  <i class="fa-solid fa-calendar"></i> Fecha - nacimiento</label>
                        <input type="date" id="alum_eda" name="fecha_nac" value="{{ old('fecha_nac', $alumno->fecha_nac) }}" class="filter-dropdown" maxlength="2">
                        @if($errors->has('fecha_nac'))
                            <span class="error-message">{{ $errors->first('fecha_nac') }}</span>
                        @endif
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="alum_correro" class="filter-label"><i class="fa-solid fa-envelope"></i> Correo</label>
                        <input type="email" id="alum_correro" name="alum_correro" placeholder="ejemplo@correo.com" value="{{ old('alum_correro', $alumno->alum_correro) }}" class="filter-dropdown">
                        @if($errors->has('alum_correo'))
                            <span class="error-message">{{ $errors->first('alum_correo') }}</span>
                        @endif
                    </div>
                    <div class="filter-item">
                        <label for="alum_telefo" class="filter-label"><i class="fa-solid fa-phone"></i> Teléfono</label>
                        <input type="tel" id="alum_telefo" name="alum_telefo" placeholder="999 999 999" value="{{ old('alum_telefo', $alumno->alum_telefo) }}" class="filter-dropdown" maxlength="9" required pattern="\d{9}">
                        @if($errors->has('alum_telefo'))
                            <span class="error-message">{{ $errors->first('alum_telefo') }}</span>
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
                    <i class="fa-solid fa-heartbeat"></i>
                    Información Médica
                </h3>
                <p class="section-description">Condiciones médicas relevantes para el entrenamiento</p>
            </div>
            <div class="section-content">
                <div class="filter-item ">
                    <label for="alum_condi" class="filter-label"><i class="fa-solid fa-notes-medical"></i> ¿Tiene alguna condición?</label>
                    <textarea name="alum_condi" id="alum_condi" rows="4"  placeholder="Describa cualquier condición médica, lesión previa, medicamentos o restricciones que debamos conocer..." class="filter-dropdown textarea">{{ old('alum_condi', $alumno->alum_condi) }}</textarea>
                </div>

            </div>
        </div>

    </div>
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="fa-solid fa-building"></i>
                    Información de Registro
                </h3>
                <p class="section-description">Lugar donde se realizará el registro</p>
            </div>
                <div class="section-content">
                    <div class="filter-row">
                        
                            @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                            <div class="filter-item">
                                <label for="fkuser" class="filter-label"><i class="fa-solid fa-location-dot"></i> Usuario de registro</label>
                                <select name="fkuser" id="fkuser" class="filter-dropdown">
                                    <option value="">Seleccione el usuario</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('fkuser', $alumno->fkuser) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                    @if($errors->has('fkuser'))
                                    <span class="error-message">{{ $errors->first('fkuser') }}</span>
                                    @endif
                            </div>
                            @else
                                <input type="hidden" name="fkuser" value="{{ auth()->user()->id }}">
                            @endif
                            
                            
                        
                            <div class="filter-item">
                                <label for="fksede" class="filter-label"><i class="fa-solid fa-location-dot"></i> Lugar de Registro</label>
                                <select name="fksede" id="fksede" class="filter-dropdown">
                                    <option value="">Seleccione Lugar de Registro</option>
                                    @foreach($sedes as $sede)
                                        <option value="{{ $sede->id_sede }}" {{ old('fksede', $alumno->fksede) == $sede->id_sede ? 'selected' : '' }}>
                                            {{ $sede->sede_nombre }}
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

    </div>


</div>


<div class="form-actions-enhanced">
    <a href="{{route('alumno.index')}}" class="btn btn-cancelar">
        <i class="fa-solid fa-times"></i>
        Cancelar
    </a>
    <button type="submit" class="btn btn-primary"> <i class="icono-guardar"></i> {{$btnText}}</button>
</div>


<script>

    document.getElementById('generate-code-btn').addEventListener('click', function() {
        const code = ('0000' + Math.floor(Math.random() * 9999)).slice(-4); // Genera un número entre 0000 y 9999
        document.getElementById('alum_codigo').value = code;
    });

    document.getElementById('customFile').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('imagePreview');
    const fileNameSpan = document.getElementById('fileName');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewContainer.innerHTML = '';
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = "Vista previa de la imagen seleccionada";
            img.className = "preview-img";
            previewContainer.appendChild(img);
            fileNameSpan.textContent = file.name;
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.innerHTML = `
            <div class="sin-imagen">
                <i class="icon-image"></i>
                <span>No hay imagen disponible</span>
            </div>
        `;
        fileNameSpan.textContent = '';
    }
    });
    document.getElementById('alum_documento').addEventListener('change',function (){
       const tipo=this.value;
       const input = document.getElementById('numero');

       if (tipo==='DNI'){
           input.malength = 8;
           input.required =true;
       }else if(tipo ==='DNIe'){
           input.malength = 8;
           input.required =true;
       }else if(tipo ==='PASS'){
           input.malength = 9;
           input.required =true;
       }else if(tipo ==='CE'){
           input.malength = 9;
           input.required =true;
       }else{
           input.removeAttribute('required');
           input.value = '';
       }
    });

</script>
