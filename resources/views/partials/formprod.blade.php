@csrf

<div class="filter-item">
    <div class="form-section">
        <div class="section-header">
            <h3 class="Sub_titulos">
                <i class="fa-solid fa-box-open"></i>
                Imagen del Producto
            </h3>
            <p class="section-description">Sube o cambia la imagen representativa del producto</p>
        </div>
        <div class="section-content">
            <div class="filter-item image-upload-section">
                <label class="filter-label enhanced-label">
                    <i class="fa-solid fa-image"></i>
                    Imagen Actual
                </label>
                <div class="contenido-img">
                    <div class="producto-image-preview" id="imagePreview">
                        @if($producto->prod_img)
                            <img src="{{ asset($producto->prod_img) }}" alt="Vista previa de {{ $producto->prod_nombre }}" class="producto-image">
                        @else
                            <div class="producto-no-image">
                                <span>No hay imagen disponible</span>
                            </div>
                        @endif
                    </div>
                    <div class="image-upload-controls">
                        <label For="customFile" class="upload-btn">
                            @if($producto->prod_img)
                                <i class="fa-solid fa-sync"></i> Cambiar imagen
                            @else
                                <i class="fa-solid fa-cloud-upload-alt"></i>
                                Seleccionar imagen
                            @endif
                        </label>
                        <input type="file" name="prod_img" id="customFile" class="file-input-hidden" data-current-file="{{ $producto->prod_img }}" accept="image/*">
                        <span class="file-info" id="fileName">
                            @if($producto->prod_img)

                            {{ basename($producto->prod_img) }}
                            @endif
                        </span>
                        @if($errors->has('prod_img'))
                            <span class="error-message">{{ $errors->first('prod_img ') }}</span>
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
                        <i class="fa-solid fa-info-circle"></i>
                        Datos Generales del Producto
                    </h3>
                    <p class="section-description">Información básica como nombre y categoría del producto</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="prod_nombre" class="filter-label">
                                <i class="fa-solid fa-tag"></i> Nombre del Producto
                            </label>
                         @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                            <input type="text" id="prod_nombre" name="prod_nombre"  placeholder="Ingrese nombre de Producto" value="{{ old('prod_nombre', $producto->prod_nombre)}}" class="filter-dropdown">
                            @if($errors->has('prod_nombre'))
                                <span class="error-message">{{ $errors->first('prod_nombre') }}</span>
                            @endif
                        @else
                            <input type="text" id="prod_nombre" name="prod_nombre" placeholder="Ingrese nombre de Producto" value="{{ old('prod_nombre', $producto->prod_nombre)}}" class="filter-dropdown" disabled>
                        @endif
                        </div>
                        <div class="filter-item">
                            <label for="fkcategoria" class="filter-label">
                                <i class="fa-solid fa-layer-group"></i> Categoría
                            </label>
                            @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                                <select name="fkcategoria" id="fkcategoria" class="filter-dropdown">
                                    <option value="">Seleccione una Categoría</option>
                                    @foreach($categoria as $catego)
                                        <option value="{{ $catego->id_categoria}}" {{ old('fkcategoria', $producto->fkcategoria ) == $catego->id_categoria ? 'selected' : '' }}>
                                            {{ $catego->nombre}}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('fkcategoria'))
                                    <span class="error-message">{{ $errors->first('fkcategoria') }}</span>
                                @endif
                            @else
                                <select name="fkcategoria" id="fkcategoria" class="filter-dropdown" disabled>
                                    <option value="{{ $producto->categoria->id_categoria }}">
                                        {{ $producto->categoria->nombre }}
                                    </option>
                                </select>
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
                    <i class="fa-solid fa-align-left"></i>
                    Descripción del Producto
                </h3>
                <p class="section-description">Agrega una descripción detallada del producto</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="prod_descripcion" class="filter-label">
                            <i class="fa-solid fa-align-left"></i> Descripción del Producto
                        </label>
                        <textarea name="prod_descripcion" id="prod_descripcion" rows="4" placeholder="Describa el contenido del Producto que vendera" class="filter-dropdown textarea">{{ old('prod_descripcion', $producto->prod_descripcion) }}</textarea>
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
                    <i class="fa-solid fa-boxes-stacked"></i>
                    Inventario y Precio
                </h3>
                <p class="section-description">Cantidad disponible y precio actual del producto</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="prod_cantidad" class="filter-label">
                            <i class="fa-solid fa-cubes"></i> Cantidad del Producto
                        </label>
                        <input type="text" id="prod_cantidad" name="prod_cantidad" placeholder="Ingrese Cantidad" value="{{ old('prod_cantidad', $producto->prod_cantidad)}}" class="filter-dropdown">
                        @if($errors->has('prod_cantidad'))
                            <span class="error-message">{{ $errors->first('prod_cantidad') }}</span>
                        @endif
                    </div>
                    <div class="filter-item">
                        <label for="prod_precio" class="filter-label">
                            <i class="fa-solid fa-dollar-sign"></i> Precio del Producto
                        </label>
                        <div class="producto-form-unique-price-container">
                            <input type="text" id="prod_precio" name="prod_precio" placeholder="Ingrese Precio" value="{{ old('prod_precio', $producto->prod_precio)}}" class="filter-dropdown">
                        </div>
                        @if($errors->has('prod_precio'))
                            <span class="error-message">{{ $errors->first('prod_precio') }}</span>
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
                    <i class="fa-solid fa-user-gear"></i>
                    Registro Administrativo
                </h3>
                <p class="section-description">Usuario responsable y lugar asignado al producto</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                         @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                            <label for="fkusers" class="filter-label">
                                <i class="fa-solid fa-user"></i> Usuario de Registro
                            </label>

                            <select name="fkusers" id="fkusers" class="filter-dropdown">
                                <option value="">Seleccione un usuario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id}}" {{ old('fkusers', $producto->fkusers) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('fkusers'))
                                <span class="producto-form-unique-error">{{ $errors->first('fkusers') }}</span>
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
                    <div class="filter-item">
                        <label for="fksede"  class="filter-label">
                            <i class="fa-solid fa-building"></i> Lugar Para el Producto
                        </label>
                         @if (auth()->user()->is(\App\Models\User::ROL_ADMIN|| auth()->user()->is(App\Models\User::ROL_ASISTENCIA)))

                            <select name="fksede" id="fksede"  class="filter-dropdown">
                                <option value="">Seleccionar Sede</option>
                                @foreach ($sedes as $sede)
                                    <option value="{{ $sede->id_sede }}"  {{old('fksede',$producto->fksede)==$sede->id_sede? 'selected' : ''}} >
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
    </div>
</div>




<div class="form-actions-enhanced">
    <a href="{{route('producto.index')}}" class="btn btn-secondary">
        <i class="icono-volver"></i>
        Listado de Productos
    </a>
    <button type="submit" class="btn btn-primary"> <i class="icono-guardar"></i> {{$btnText}}</button>
</div>



  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('customFile');
        const previewContainer = document.querySelector('.producto-image-preview');

        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                // Crear o actualizar el elemento para mostrar el nombre del archivo
                let fileNameElement = document.querySelector('.archivo-seleccionado');
                if (!fileNameElement) {
                    fileNameElement = document.createElement('div');
                    fileNameElement.className = 'archivo-seleccionado';
                    fileInput.parentNode.appendChild(fileNameElement);
                }
                fileNameElement.textContent = 'Archivo seleccionado: ' + this.files[0].name;

                // Previsualizar la imagen
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Limpiar el contenedor de la imagen
                    previewContainer.innerHTML = '';

                    // Crear un nuevo elemento <img>
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.className = 'producto-image'; // Clase correcta
                    newImg.alt = 'Vista previa de la imagen';

                    // Agregar la imagen al contenedor
                    previewContainer.appendChild(newImg);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
  </script>
