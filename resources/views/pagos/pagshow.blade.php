@extends('layout')

@section('title', 'Detalle pago')

@section('content')
@include('partials.estado')
@include('partials.validation-errors')


{{--<div class="pago-contenedor">--}}
    <div class="pago-tarjeta">
        <div class="pago-encabezado">
            <h2 class="pago-titulo">Detalles del Pago</h2>
            <span class="pago-fecha">
        @if($pago && $pago->created_at)
                    Registrado hace {{ $pago->created_at->diffForHumans() }}
                @endif
      </span>
        </div>

        <div class="pago-contenido">
            <!-- Grid de información principal -->
            <div class="pago-grid">
                <div class="pago-item">
                    <span class="pago-etiqueta">Alumno:</span>
                    <span class="pago-valor">
            @if($pago->alumno)
                            {{ $pago->alumno->alum_nombre }} {{ $pago->alumno->alum_apellido }}
                        @else
                            <span style="color: red;">Sin alumno registrado</span>
                        @endif
          </span>
                </div>

                <div class="pago-item">
                    <span class="pago-etiqueta">Código del Alumno:</span>
                    <span class="pago-valor">
            @if($pago->alumno)
                            {{$pago->alumno->alum_codigo }}
                        @else
                            {{ 'No tiene codigo' }}
                        @endif
          </span>
                </div>

                <div class="pago-item">
                    <span class="pago-etiqueta">Membresía:</span>
                    <span class="pago-valor">{{ $pago->membresia->mem_nomb }}</span>
                </div>

                <div class="pago-item">
                    <span class="pago-etiqueta">Duración:</span>
                    <span class="pago-valor">
                        @if($pago->membresia->mem_durac)
                            {{ $pago->membresia->mem_durac }} días
                        @else
                            {{$pago->membresia->mem_limit}} 
                        @endif
                        
                    </span>
                </div>

                <div class="pago-item">
                    <span class="pago-etiqueta">Fecha de Inicio:</span>
                    <span class="pago-valor">{{ \Carbon\Carbon::parse($pago->pag_inicio)->format('d/m/Y') }}</span>
                </div>

                <div class="pago-item">
                    <span class="pago-etiqueta">Fecha de Fin:</span>
                    <span class="pago-valor">{{ \Carbon\Carbon::parse($pago->pag_fin)->format('d/m/Y') }}</span>
                </div>

                <div class="pago-item">
                    <span class="pago-etiqueta">Método de Pago:</span>
                    <span class="pago-valor">{{ $pago->metodo->tipo_pago }}</span>
                </div>

                <div class="pago-item">
                    <span class="pago-etiqueta">Lugar de Registro:</span>
                    <span class="pago-valor">{{ $pago->sede->sede_nombre }}</span>
                </div>

                <div class="pago-item">
                    <span class="pago-etiqueta">Estado de la Membresía:</span>
                    <span class="pago-valor">
              @if($pago->congelado)
                            <span style="color: red;">Congelada</span>
                        @else
                            <span style="color: green;">Activa</span>
                        @endif
          </span>
                </div>

                <div class="pago-item">
                    <span class="pago-etiqueta">Registrado por:</span>
                    <span class="pago-valor">{{ $pago->user->name }}</span>
                </div>
            </div>

            <!-- Elementos destacados que ocupan todo el ancho -->
            <div class="pago-destacados">
                <div class="pago-item pago-item-destacado">
                    <span class="pago-etiqueta">Costo Total:</span>
                    <span class="pago-valor">${{ number_format($pago->pago, 2) }}</span>
                </div>

                <div class="pago-item pago-item-estado">
                    <span class="pago-etiqueta">Estado del Pago:</span>
                    <span class="pago-valor">
            @if ($pago->estado_pago === 'completo')
                            <span class="status status-active">Pago Completo</span>
                        @elseif ($pago->estado_pago === 'incompleto')
                            <span class="status status-expiring">Pago Incompleto</span>
                        @else
                            <span class="status status-inactive">Sin estado</span>
                        @endif
          </span>
                </div>
            </div>

            <!-- Información adicional para pagos incompletos -->
            @if ($pago->estado_pago === 'incompleto')
                <div class="pago-incompleto">
                    <h3 class="pago-subtitulo">Información de Pago Pendiente</h3>
                    <div class="pago-grid-incompleto">
                        <div class="pago-item">
                            <span class="pago-etiqueta">Saldo Pendiente:</span>
                            <span class="pago-valor">${{ number_format($pago->saldo_pendiente, 2) }}</span>
                        </div>
                        <div class="pago-item">
                            <span class="pago-etiqueta">Fecha Límite para Pagar:</span>
                            <span class="pago-valor">
                @if ($pago->fecha_limite_pago)
                                    {{ \Carbon\Carbon::parse($pago->fecha_limite_pago)->format('d/m/Y') }}
                                @else
                                    Sin fecha límite
                                @endif
              </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Información de congelación -->
            <div class="pago-item pago-congelado" style="display: {{ $pago->congelado ? 'flex' : 'none' }};">
                <span class="pago-etiqueta">Días Pendientes:</span>
                <span class="pago-valor">{{ $pago->dias_pendientes }} días</span>
            </div>
        </div>

        <div class="pago-acciones">
            <a href="{{ route('pagos.completos') }}" class="boton-secundario">
                <i class="fa-solid fa-arrow-left"></i> Volver a la lista
            </a>
            <a href="{{ route('pagos.edit', $pago) }}" class="boton-primario">
                <i class="fa-solid fa-edit"></i> Editar Pago
            </a>
            @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
                @if ($pago->membresia->mem_durac >= 30 )
                    @if($pago->congelado)
                        <form action="{{ route('pagos.reanudar', $pago->id_pag) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="boton-reanudar">
                                <i class="fa-solid fa-play"></i> Reanudar Membresía
                            </button>
                        </form>
                    @else
                        <form action="{{ route('pagos.congelar', $pago->id_pag) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="boton-congelar">
                                <i class="fa-solid fa-pause"></i> Congelar Membresía
                            </button>
                        </form>
                    @endif
                @endif
            @endif
        </div>
    </div>
{{--</div>--}}

@endsection

<script>
  document.addEventListener('DOMContentLoaded', function () {
      // Agregar confirmación para el formulario de congelar
      const congelarForm = document.querySelector('form[action*="congelar"]');
      if (congelarForm) {
          congelarForm.addEventListener('submit', function (e) {
              if (!confirm('¿Estás seguro de que deseas congelar esta membresía?')) {
                  e.preventDefault(); // Cancelar el envío si el usuario cancela
              }
          });
      }


      const reanudarForm = document.querySelector('form[action*="reanudar"]');
      if (reanudarForm) {
          reanudarForm.addEventListener('submit', function (e) {
              if (!confirm('¿Estás seguro de que deseas reanudar esta membresía?')) {
                  e.preventDefault(); // Cancelar el envío si el usuario cancela
              }
          });
      }
  });
</script>
