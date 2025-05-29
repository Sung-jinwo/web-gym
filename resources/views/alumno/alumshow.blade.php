@extends('layout')

@section('title', 'Alumno | ' . ($alumno ? $alumno->alum_apellido : 'No encontrado'))

@section('content')
@include('partials.estado')


    <div class="info-card">
        <div class="info-card__image">
            @if ($alumno->alum_img)
                <img src="{{ asset($alumno->alum_img) }}" alt="Vista previa de {{ $alumno->alum_nombre }} {{ $alumno->alum_apellido }}">
            @else
                <div class="no-image">
                    <i class="fa-solid fa-user"></i>
                    <span>No tiene imagen</span>
                </div>
            @endif
        </div>
        <div class="info-card__content">
            <div class="info-card__header">
                <h1 class="info-card__title">
                    <i class="fa-solid fa-user-check"></i> {{ $alumno->alum_nombre }} {{ $alumno->alum_apellido }}
                </h1>
                <div class="info-card__status">
                    <span class="status @if($alumno->alum_estado == 'A') status-active @else status-inactive @endif">
                        <i class="fa-solid fa-circle"></i> @if($alumno->alum_estado == 'A') Activo @else Inactivo @endif
                    </span>
                </div>
            </div>
            <div class="info-card__grid">
                <div class="info-card__grid-item">
                    <span class="info-card__label"><i class="fa-solid fa-hashtag"></i> Código:</span>
                    <span class="info-card__value">{{ $alumno->alum_codigo }}</span>
                </div>
                <div class="info-card__grid-item">
                    <span class="info-card__label"><i class="fa-solid fa-id-card"></i> Documento de Identidad:</span>
                    <span class="info-card__value">
                        @if($alumno->alum_documento)
                            {{ $alumno->alum_documento }}
                        @else
                            No tiene documento de Identidad
                        @endif
                    </span>
                </div>
                <div class="info-card__grid-item">
                    <span class="info-card__label"><i class="fa-solid fa-credit-card"></i> Numero Documento:</span>
                    <span class="info-card__value">
                    @if($alumno->alum_numDoc)
                            {{ $alumno->alum_numDoc }}
                    @else
                        No tiene numero de documento
                    @endif
                    </span>
                </div>
                <div class="info-card__grid-item">
                    <span class="info-card__label"><i class="fa-solid fa-user"></i> Sexo:</span>
                    <span class="info-card__value">@if ($alumno->fksede == 1) Masculino @else Femenino @endif</span>
                </div>
                <div class="info-card__grid-item">
                    <span class="info-card__label"><i class="fa-solid fa-calendar"></i> Edad:</span>
                    <span class="info-card__value">{{ $alumno->alum_eda }} años</span>
                </div>
                <div class="info-card__grid-item">
                    <span class="info-card__label"><i class="fa-solid fa-envelope"></i> Correo:</span>
                    <span class="info-card__value">{{ $alumno->alum_correro }}</span>
                </div>
                <div class="info-card__grid-item">
                    <span class="info-card__label"><i class="fa-solid fa-phone"></i> Teléfono:</span>
                    <span class="info-card__value">{{ $alumno->alum_telefo }}</span>
                </div>
                <div class="info-card__grid-item">
                    <span class="info-card__label"><i class="fa-solid fa-building"></i> Lugar de Registro:</span>
                    <span class="info-card__value">{{ $alumno->sede->sede_nombre }}</span>
                </div>
            </div>
            <div class="info-card__actions">
                <a href="{{ route('alumno.index') }}" class="btn-icon btn-blue" title="Volver al listado">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <a href="{{ route('alumno.edit', $alumno) }}" class="btn-icon btn-green" title="Editar alumno">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="{{ route('asistencia.create') }}?alumno_id={{ $alumno->id_alumno}}" class="btn-icon btn-blue" title="Asistencia alumno">
                    <i class="fa-solid fa-calendar"></i>
                </a>
                <a href="{{ route('pagos.create') }}?alumno_id={{ $alumno->id_alumno }}" class="btn-icon btn-primary" title="Registrar pago">
                    <i class="fa-solid fa-dollar-sign"></i>
                </a>
                <form action="{{ route('alumno.cambiarEstado', $alumno) }}" method="POST" class="inline-form">
                    @csrf
                    @method('PUT')
                    @if ($alumno->alum_estado == 'A')
                        <button type="submit" class="btn-icon btn-red" title="Inhabilitar alumno" onclick="return confirm('¿Estás seguro de desactivar este alumno?')">
                            <i class="fa-solid fa-times-circle"></i>
                        </button>
                    @elseif($alumno->alum_estado == 'E')
                        <button type="submit" class="btn-icon btn-green" title="Reactivar alumno" onclick="return confirm('¿Estás seguro de Activar este alumno?')">
                            <i class="fa-solid fa-check-circle"></i>
                        </button>
                    @endif
                </form>
                @if(auth()->user()->is(\App\Models\User::ROL_ADMIN))
                <form action="{{ route('alumno.destroy', $alumno) }}" method="POST" class="inline-form">
                    @csrf @method('DELETE')
                    @if ($alumno->alum_estado == 'E')
                        <button type="submit" class="btn-icon btn-red" title="Eliminar alumno" onclick="return confirm('¿Estás seguro de eliminar al alumno?')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    @endif
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Sección de membresías -->
    <div class="membership-section">
        <h2 class="section-title"><i class="fa-solid fa-award"></i> Membresías</h2>
        <div class="membership-cards">
            <!-- Membresía principal -->
            <div class="membership-card">
                <div class="membership-card__header">
                    <h3 class="membership-card__title"><i class="fa-solid fa-star"></i> Membresía Principal</h3>
                </div>
                <div class="membership-card__content">
                    @if($membresiaPrincipal)
                        <div class="membership-card__grid">
                            <div class="membership-card__grid-item">
                                <span class="membership-card__label"><i class="fa-solid fa-tag"></i> Nombre:</span>
                                <span class="membership-card__value">{{ $membresiaPrincipal->membresia->mem_nomb }}</span>
                            </div>
                            <div class="membership-card__grid-item">
                                <span class="membership-card__label"><i class="fa-solid fa-clock"></i> Duración:</span>
                                <span class="membership-card__value">{{ $membresiaPrincipal->membresia->mem_durac }} días</span>
                            </div>
                            <div class="membership-card__grid-item">
                                <span class="membership-card__label"><i class="fa-solid fa-calendar"></i> Inicio:</span>
                                <span class="membership-card__value">{{ $membresiaPrincipal->pag_inicio_formato }}</span>
                            </div>
                            <div class="membership-card__grid-item">
                                <span class="membership-card__label"><i class="fa-solid fa-calendar"></i> Fin:</span>
                                <span class="membership-card__value">{{ $membresiaPrincipal->pag_fin_formato }}</span>
                            </div>
                            <div class="membership-card__grid-item">
                                <span class="membership-card__label"><i class="fa-solid fa-solid fa-building"></i> Lugar de Pago:</span>
                                <span class="membership-card__value">{{ $membresiaPrincipal->sede->sede_nombre }}</span>
                            </div>
                            <div class="membership-card__grid-item">
                                <span class="membership-card__label"><i class="fa-solid fa-circle-check"></i> Estado:</span>
                                @php
                                    $hoy = now();
                                    $fechaFin = \Carbon\Carbon::parse($membresiaPrincipal->pag_fin);
                                    if ($fechaFin->isPast()) {
                                        $estado = 'Vencido';
                                        $clase = 'status-expired';
                                    } elseif ($fechaFin->diffInDays($hoy) <= 5) {
                                        $estado = 'Por caducar / Renovar';
                                        $clase = 'status-expiring';
                                    } else {
                                        $estado = 'Vigente';
                                        $clase = 'status-active';
                                    }
                                @endphp
                                <span class="status {{ $alumno->claseEstado }}">{{ $alumno->textoMembresia }}</span>
                            </div>
                            <div class="membership-card__grid-item">
                                <span class="membership-card__label"><i class="fa-solid fa-pause"></i> Congelada:</span>
                                <span class="membership-card__value">
                                    @if($membresiaPrincipal->congelado)
                                        <span style="color: red;">Sí</span>
                                    @else
                                        <span style="color: green;">No</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="membership-card__actions">
                            <a href="{{ route('alumno.contratopdf', $alumno) }}" class="btn-icon btn-yellow" title="Descargar Contrato (PDF)">
                                <i class="fa-solid fa fa-file"></i>
                            </a>
                            <a href="{{ route('alumno.boletapdf', $alumno) }}" class="btn-icon btn-red" title="Descargar boleta (PDF)">
                                <i class="fa-solid fa fa-file"></i>
                            </a>
                            <a href="{{ route('pagos.edit', $membresiaPrincipal) }}" class="btn-icon btn-green" title="Editar Pago">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('pagos.show', $membresiaPrincipal) }}" class="btn-icon btn-blue" title="Ver detalles del pago">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($alumno->claseEstado != 'Vigente' || $membresiaPrincipal->congelado)
                                <a href="{{ route('pagos.create') }}?alumno_id={{ $alumno->id_alumno }}" class="btn-icon btn-primary" title="Renovar membresía">
                                    <i class="fa-solid fa-rotate"></i>
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="membership-card__empty">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <p>No hay membresía principal registrada.</p>
                            <a href="{{ route('pagos.create') }}?alumno_id={{ $alumno->id_alumno }}" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Registrar Membresía
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Membresías adicionales -->
            <div class="membership-card">
                <div class="membership-card__header">
                    <h3 class="membership-card__title"><i class="fa-solid fa-layer-group"></i> Membresías Adicionales</h3>
                </div>
                <div class="membership-card__content">
                    @if($membresiasAdicionales->count())
                        <div class="membership-table-container">
                            <table class="membership-table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Congelada</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($membresiasAdicionales as $pago)
                                        <tr>
                                            <td>{{ $pago->membresia->mem_nomb }}</td>
                                            <td>{{ $pago->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @php
                                                    $hoy = now();
                                                    $fechaFin = \Carbon\Carbon::parse($pago->pag_fin);
                                                    if ($fechaFin->isPast()) {
                                                        $estado = 'Vencido';
                                                        $clase = 'status-expired';
                                                    } elseif ($fechaFin->diffInDays($hoy) <= 5) {
                                                        $estado = 'Por caducar';
                                                        $clase = 'status-expiring';
                                                    } else {
                                                        $estado = 'Vigente';
                                                        $clase = 'status-active';
                                                    }
                                                @endphp
                                                <span class="status {{ $clase }}">{{ $estado }}</span>
                                            </td>
                                            <td>
                                                @if($pago->congelado)
                                                    <span style="color: red;">Sí</span>
                                                @else
                                                    <span style="color: green;">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('pagos.show', $pago) }}" class="btn-icon btn-blue" title="Ver detalles">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="membership-card__empty">
                            <i class="fa-solid fa-info-circle"></i>
                            <p>No hay membresías adicionales registradas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de padres -->
    <div class="parents-section">
        <h2 class="section-title"><i class="fa-solid fa-users"></i> Información de Padres</h2>
        <div class="parents-content">
            @if ($tienePadres)
                <div class="parents-grid">
                    @foreach ($alumno->padres as $padre)
                        <div class="parent-card">
                            <div class="parent-card__header">
                                <i class="fa-solid fa-user"></i>
                                <h3 class="parent-card__name">{{ $padre->padre_nombre }} {{ $padre->padre_apellido }}</h3>
                            </div>
                            <div class="parent-card__content">
                                <div class="parent-card__item">
                                    <span class="parent-card__label"><i class="fa-solid fa-phone"></i> Teléfono:</span>
                                    <span class="parent-card__value">{{ $padre->padre_telefono }}</span>
                                </div>
                                <div class="parent-card__item">
                                    <span class="parent-card__label"><i class="fa-solid fa-envelope"></i> Correo:</span>
                                    <span class="parent-card__value">{{ $padre->padre_correo ?? 'No especificado' }}</span>
                                </div>
                                <div class="parent-card__item">
                                    <span class="parent-card__label"><i class="fa-solid fa-user"></i> Sexo:</span>
                                    <span class="parent-card__value">
                                        @if ($padre->fksexo == 1)
                                            Masculino
                                        @elseif ($padre->fksexo == 2)
                                            Femenino
                                        @else
                                            No especificado
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="parent-card__actions">
                                <a href="{{ route('padres.edit', $padre) }}" class="btn-icon btn-green" title="Editar padre">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="parents-empty">
                    <i class="fa-solid fa-users"></i>
                    <p>Este alumno no tiene padres registrados.</p>
                    @if ($alumno->alum_eda < 18)
                        <a href="{{ route('padres.create') }}?alumno_id={{ $alumno->id_alumno }}" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus"></i> Registrar Padre
                        </a>
                    @else
                        <p class="parents-note"><i class="fa-solid fa-info-circle"></i> El alumno es mayor de 18 años. No aplica para el registro de padres.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

@endsection
