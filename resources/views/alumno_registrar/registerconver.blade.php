@extends('layout')

@section('title', 'Conversar Prospecto')

@section('content')
@include('partials.validation-errors')

<h1>Registrar Nuevo Mensaje</h1>


<form action="{{ route('registro.mensaje', $registros) }}" method="POST" enctype="multipart/form-data" >
    @method('PATCH')
    @csrf
    <div class="filter-row" >
        <div class="filter-item">
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos">
                        <i class="fa-solid fa-comments"></i>
                        Detalle del Prospecto De {{ $registros->alumno->sede->sede_nombre}}
                    </h3>
                    <p class="section-description">Descripcion brevemente del cliente correspondiente.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="mensa_problema" class="filter-label">
                                <i class="icono-usuario"></i> Nombre Completo
                            </label>
                            <input type="text"  class="filter-dropdown"
                                   value="{{ $registros->alumno->nombre_completo }}"
                                  readonly>
                        </div>

                        <div class="filter-item">
                            <label for="mensa_problema" class="filter-label">
                                <i class="fa-solid fa-comment-dots"></i> Edad del Alumno
                            </label>
                            <input type="text"  class="filter-dropdown"
                                   value="{{ $registros->alumno->alum_eda }}"
                                   readonly>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-row" >
        <div class="filter-item">
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos">
                        <i class="fa-solid fa-comments"></i>
                        Detalle del Mensaje
                    </h3>
                    <p class="section-description">Describe brevemente el problema o consulta y selecciona el área correspondiente.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="mensa_problema" class="filter-label">
                                <i class="fa-solid fa-comment-dots"></i> Problema o Consulta
                            </label>
                            <input type="text" id="mensa_problema" name="mensa_problema" class="filter-dropdown"
                                   value="{{ old('mensa_problema', $registros->mensa_problema) }}"
                                   maxlength="100" placeholder="Ej. El alumno reporta fallas en el sistema de inscripción">
                            @if($errors->has('mensa_problema'))
                                <span class="error-message">{{ $errors->first('mensa_problema') }}</span>
                            @endif
                        </div>

                        <div class="filter-item">
                            <label for="mensa_area" class="filter-label">
                                <i class="fa-solid fa-layer-group"></i> Área
                            </label>
                            <select name="mensa_area" id="mensa_area" class="filter-dropdown">
                                <option value="">Seleccione un área</option>
                                <option value="Maquinas" {{ old('mensa_area', $registros->mensa_area) == 'Maquinas' ? 'selected' : '' }}>Máquinas</option>
                                <option value="Baile" {{ old('mensa_area', $registros->mensa_area) == 'Baile' ? 'selected' : '' }}>Baile</option>
                                <option value="Personalizado" {{ old('mensa_area', $registros->mensa_area) == 'Personalizado' ? 'selected' : '' }}>Personalizado</option>
                                <option value="PaqueteCompleto" {{ old('mensa_area', $registros->mensa_area) == 'PaqueteCompleto' ? 'selected' : '' }}>Paquete Completo</option>
                            </select>
                            @if($errors->has('mensa_area'))
                                <span class="error-message">{{ $errors->first('mensa_area') }}</span>
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
                        <i class="fa-solid fa-calendar-alt"></i>
                        Información del Seguimiento
                    </h3>
                    <p class="section-description">Periodo del mensaje y respuesta del alumno ante el contacto.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="mensa_periodo" class="filter-label">
                                <i class="fa-solid fa-calendar-alt"></i> Periodo
                            </label>
                            <select name="mensa_periodo" id="mensa_periodo" class="filter-dropdown">
                                <option value="">Seleccione un periodo</option>
                                <option value="1 Mes" {{ old('mensa_periodo', $registros->mensa_periodo) == '1 Mes' ? 'selected' : '' }}>1 Mes</option>
                                <option value="3 Meses"{{ old('mensa_periodo', $registros->mensa_periodo) == '3 Meses' ? 'selected' : '' }}>3 Meses</option>
                                <option value="6 Meses" {{ old('mensa_periodo', $registros->mensa_periodo) == '6 Meses' ? 'selected' : '' }}>6 Meses</option>
                                <option value="1 Año" {{ old('mensa_periodo', $registros->mensa_periodo) == '1 Año' ? 'selected' : '' }}>1 Año</option>
                            </select>
                            @if($errors->has('mensa_periodo'))
                                <span class="error-message">{{ $errors->first('mensa_periodo') }}</span>
                            @endif
                        </div>

                        <div class="filter-item">
                            <label for="respuesta_alumno" class="filter-label">
                                <i class="fa-solid fa-reply"></i> Respuesta del Alumno
                            </label>
                            <input type="text" id="respuesta_alumno" name="respuesta_alumno" class="filter-dropdown"
                                   value="{{ old('respuesta_alumno', $registros->respuesta_alumno) }}"
                                   maxlength="100" placeholder="Ej. El alumno aceptó continuar con el proceso">
                            @if($errors->has('respuesta_alumno'))
                                <span class="error-message">{{ $errors->first('respuesta_alumno') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="filter-row" >
        <div class="filter-item">
            <div class="form-section">
                <div class="section-header">
                    <h3 class="Sub_titulos">
                        <i class="fa-solid fa-envelope-circle-check"></i>
                        Estadísticas de Contacto
                    </h3>
                    <p class="section-description">Cantidad de mensajes enviados y llamadas respondidas.</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label for="numero_mensaje" class="filter-label">
                                <i class="fa-solid fa-paper-plane"></i> Mensajes Enviados
                            </label>
                            <div class="toggle-options">
                                @for ($i = 1; $i <= 10; $i++)
                                    <div class="Enviados">
                                        <input type="radio" id="numero_mensaje_{{ $i }}" name="numero_mensaje" value="{{ $i }}"
                                               {{ old('numero_mensaje', $registros->numero_mensaje) == $i ? 'checked' : '' }} class="custom-radio">
                                        <label for="numero_mensaje_{{ $i }}" class="toggle-label">{{ $i }}</label>
                                    </div>
                                @endfor
                            </div>
                            @if($errors->has('numero_mensaje'))
                                <span class="error-message">{{ $errors->first('numero_mensaje') }}</span>
                            @endif
                        </div>

                        <div class="filter-item">
                            <label for="mensa_llamar" class="filter-label">
                                <i class="fa-solid fa-phone-volume"></i> Llamadas Respondidas
                            </label>
                            <div class="toggle-options">
                                <div class="toggle-option">
                                    <input type="radio" id="mensa_llamar_0" name="mensa_llamar" value="{{0}}"
                                           {{ old('mensa_llamar', $registros->mensa_llamar) == '0' ? 'checked': '' }} class="custom-radio">
                                    <label for="mensa_llamar_0" class="toggle-label">Ninguna</label>
                                </div>
                                @for ($i = 1; $i <= 2; $i++)
                                    <div class="toggle-option">
                                        <input type="radio" id="mensa_llamar_{{ $i }}" name="mensa_llamar" value="{{ $i }}"
                                               {{ old('mensa_llamar', $registros->mensa_llamar) == $i ? 'checked' : '' }} class="custom-radio">
                                        <label for="mensa_llamar_{{ $i }}" class="toggle-label">Llamada {{ $i }}</label>
                                    </div>
                                @endfor
                            </div>
                            @if($errors->has('mensa_llamar'))
                                <span class="error-message">{{ $errors->first('mensa_llamar') }}</span>
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
                        <i class="fa-solid fa-clipboard-question"></i>
                        Estado del Proceso
                    </h3>
                    <p class="section-description">¿El proceso terminó en una venta o se postergó?</p>
                </div>
                <div class="section-content">
                    <div class="filter-row">
                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-handshake"></i> ¿Se cerró la venta?
                            </label>
                            <div class="toggle-options">
                                <div class="toggle-option">
                                    <input type="radio" id="mensa_cerrar_0" name="mensa_cerrar" value="P"
                                           {{ old('mensa_cerrar', $registros->mensa_cerrar) == 'P' ? 'checked' : '' }} class="custom-radio">
                                    <label for="mensa_cerrar_0" class="toggle-label">En Proceso</label>
                                </div>
                                <div class="Enviados">
                                    <input type="radio" id="mensa_cerrar_1" name="mensa_cerrar" value="S"
                                           {{ old('mensa_cerrar', $registros->mensa_cerrar) == 'S' ? 'checked' : '' }} class="custom-radio">
                                    <label for="mensa_cerrar_1" class="toggle-label">Sí</label>
                                </div>
                                <div class="cerrar">
                                    <input type="radio" id="mensa_cerrar_2" name="mensa_cerrar" value="N"
                                           {{ old('mensa_cerrar', $registros->mensa_cerrar) == 'N' ? 'checked' : '' }} class="custom-radio">
                                    <label for="mensa_cerrar_2" class="toggle-label">No</label>
                                </div>
                            </div>
                            @if($errors->has('mensa_cerrar'))
                                <span class="error-message">{{ $errors->first('mensa_cerrar') }}</span>
                            @endif
                        </div>

                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-clock-rotate-left"></i> ¿Se va a postergar?
                            </label>
                            <div class="toggle-switch-container">
                                <div class="toggle-switch">
                                    <input type="checkbox" id="mostrar" name="postergar" value="Si"
                                           {{ old('postergar', $registros->postergar) == 'Si' ? 'checked' : '' }} class="toggle-input">
                                    <label for="mostrar" class="toggle-slider"></label>
                                </div>
                                <span class="toggle-label-text" id="toggle-status">
                        {{ old('postergar', $registros->postergar) == 'Si' ? 'Sí' : 'No' }}
                    </span>
                                <div id="EditarFecha" class="EditarFecha" style="display: none">
                                    <input type="month" id="mensa_edit" name="mensa_edit" class="filter-dropdown" placeholder="Mes y año de postergación">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="form-actions-enhanced">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save"></i> Registrar Mensaje
        </button>
    </div>


</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleInput = document.getElementById('mostrar');
        const toggleStatus = document.getElementById('toggle-status');
        const EditarFecha = document.getElementById('EditarFecha');
        const mensaEditInput  = document.getElementById('mensa_edit');

        function actualizarEstado() {
            if (toggleInput.checked) {
                toggleStatus.textContent = 'Sí';
                EditarFecha.style.display = 'block';
                if(mensaEditInput){
                    mensaEditInput.value ='{{old('mensa_edit',$registros->fecha_formato)}}'
                }
            } else {
                toggleStatus.textContent = 'No';
                EditarFecha.style.display = 'none';
                if (mensaEditInput) {
                mensaEditInput.value = '';
                }
            }
        }
        actualizarEstado();

        // Añadir el evento change al checkbox
        if (toggleInput) {
            toggleInput.addEventListener('change', actualizarEstado);
        }
    });
</script>


@endsection
