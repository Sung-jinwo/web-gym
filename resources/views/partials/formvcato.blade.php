@csrf

<div class="form-section">
    <div class="section-header">
        <h3 class="Sub_titulos">
            <i class="icono-detalle"></i>
            Nombre Especifico de Categoria
        </h3>
        <p class="section-description">Ingrese un Categoria para el Producto Nuevo</p>
    </div>
    <div class="section-content">
        <div class="filter-row">
            <div class="filter-item">
                <label for="fkuser" class="filter-label">
                    <i class="fa-solid fa-user"></i> Usuario de Registro
                </label>
                <input type="hidden" name="fkuser" value="{{ old('fkuser', auth()->user()->id) }}">
                <input type="text" class="filter-dropdown" readonly value="{{ auth()->user()->name }}">
                @if($errors->has('fkuser'))
                    <span class="categoria-edit-unique-error">
                            <i class="fa-solid fa-exclamation-circle"></i> {{ $errors->first('fkuser') }}
                        </span>
                @endif
            </div>
            <div class="filter-item">
                <label for="nombre_m" class="filter-label">
                    <i class="fa-solid fa-tag"></i> Nombre de la Categoría
                </label>
                <input type="text" id="nombre" name="nombre" placeholder="Ingrese nombre de categoria" value="{{ old('nombre', $categoria->nombre)}}" class="filter-dropdown">
                @if($errors->has('nombre'))
                    <span class="categoria-form-unique-error">
                    <i class="fa-solid fa-exclamation-circle"></i> {{ $errors->first('nombre') }}
                </span>
                @endif
            </div>
        </div>
    </div>
</div>




<div class="form-actions-enhanced">
    <a href="{{route('producto.index')}}" class="btn btn-secondary">
        <i class="icono-volver"></i> Lista de Membresias
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="icono-guardar"></i> {{$btnText}}
    </button>
</div>
