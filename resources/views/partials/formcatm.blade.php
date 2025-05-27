@csrf

    <div class="form-section">
        <div class="section-header">
            <h3 class="Sub_titulos">
                <i class="icono-detalle"></i>
                Nombre Especifico de Categoria
            </h3>
            <p class="section-description">Ingrese un Categoria para la Membresia Nuevo</p>
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
                        <span class="error-message">
                            <i class="fa-solid fa-exclamation-circle"></i> {{ $errors->first('fkuser') }}
                        </span>
                    @endif
                </div>
                <div class="filter-item">

                    <label for="nombre_m" class="filter-label">
                        <i class="fa-solid fa-tag"></i> Nombre de la Categoría
                    </label>
                    <input type="text" id="nombre_m" name="nombre_m" placeholder="Ingrese nombre de categoria" value="{{ old('nombre_m', $categoria_m->nombre_m)}}" class="filter-dropdown">
                    @if($errors->has('nombre_m'))
                        <span class="error-message">
                        <i class="fa-solid fa-exclamation-circle"></i> {{ $errors->first('nombre_m') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>


<div class="form-actions-enhanced">
    <a href="{{route('membresias.index')}}" class="btn btn-secondary">
        <i class="icono-volver"></i> Lista de Membresias
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="icono-guardar"></i> {{$btnText}}
    </button>
</div>



