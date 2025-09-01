@csrf

<div class="filter-row">
    <div class="filter-item">
        <div class="form-section">
            <div class="section-header">
                <h3 class="Sub_titulos">
                    <i class="icono-fecha"></i>
                    Informacion Para Membresia
                </h3>
                <p class="section-description">Complete los campos apra una nueva membresia</p>
            </div>
            <div class="section-content">
                <div class="filter-row">
                    <div class="filter-item">
                        <label class="filter-label" for="mem_nomb"><i class="icono-nombre"></i> Nombre de la Membresia</label>
                        <input type="text" id="mem_nomb" name="mem_nomb" placeholder="Ingrese nombre membresia" value="{{ old('mem_nomb', $membresias->mem_nomb) }}" class="filter-dropdown">
                        @if($errors->has('mem_nomb'))
                            <span class="error-message">{{ $errors->first('mem_nomb') }}</span>
                        @endif
                    </div>
                    <div class="filter-item">
                        <label for="fkcategoria" class="filter-label"><i class="icono-categoria"></i> Categoria</label>
                        <select name="fkcategoria" id="fkcategoria" class="filter-dropdown">
                            <option value="">Seleccione una Categoria</option>
                            @foreach($categoria_m as $catego)
                                <option value="{{ $catego->id_categoria_m}}" {{ old('fkcategoria', $membresias->fkcategoria ) == $catego->id_categoria_m ? 'selected' : '' }}>
                                    {{ $catego->nombre_m}}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('fkcategoria'))
                            <span class="error-message">{{ $errors->first('fkcategoria') }}</span>
                        @endif
                    </div>
                    <div class="filter-item">
                        <label for="mem_durac" class="filter-label"><i class="icono-duracion"></i> Duracion (dias)</label>
                        <input type="text" id="mem_durac" name="mem_durac" placeholder="Ingrese numero de dias" value="{{ old('mem_durac', $membresias->mem_durac) }}" class="filter-dropdown">
                        @if($errors->has('mem_durac'))
                            <span class="error-message">{{ $errors->first('mem_durac') }}</span>
                        @endif
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-item">
                        <label for="mem_limit" class="filter-label enhanced-label">
                            <i class="fa-solid fa-calendar-minus"></i>
                            Fecha Limite
                        </label>
                        <input type="date" name="mem_limit" id="mem_limit" value="{{ old('mem_limit', $membresias->mem_limit) }}" class="filter-dropdown enhanced-input">
                        @if($errors->has('mem_limit'))
                            <span class="error-message">{{ $errors->first('mem_limit') }}</span>
                        @endif
                    </div>
                    <div class="filter-item">
                        <label for="mem_cost" class="filter-label"><i class="icono-costo"></i> Costo</label>
                        <input type="text" id="mem_cost" name="mem_cost" placeholder="Ingrese Precio" value="{{ old('mem_cost', $membresias->mem_cost) }}" class="filter-dropdown">
                        @if($errors->has('mem_cost'))
                            <span class="error-message">{{ $errors->first('mem_cost') }}</span>
                        @endif
                    </div>
                    <div class="filter-item">
                        <label for="mem_comi" class="filter-label"><i class="icono-costo"></i> Comision</label>
                        <input type="text" id="mem_comi" name="mem_comi" placeholder="Ingrese La comision" value="{{ old('mem_comi', $membresias->mem_comi) }}" class="filter-dropdown">
                        @if($errors->has('mem_comi'))
                            <span class="error-message">{{ $errors->first('mem_comi') }}</span>
                        @endif
                    </div>
                    <div class="filter-item">
                        <label for="tipo" class="filter-label"><i class="fa-solid fa-layer-group"></i> Tipo de Membresia</label>
                        <select name="tipo" id="tipo"  class="filter-dropdown">
                            <option value="">Seleccionar el tipo de membresia</option>
                            <option value="principal" {{ old('tipo', $membresias->tipo) == 'principal' ? 'selected' : '' }}>Principal</option>
                            <option value="adicional" {{ old('tipo', $membresias->tipo) == 'adicional' ? 'selected' : '' }}>Adicional</option>
                        </select>
                        @if($errors->has('tipo'))
                            <span class="error-message">{{ $errors->first('tipo') }}</span>
                        @endif
                    </div>
                </div>
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



