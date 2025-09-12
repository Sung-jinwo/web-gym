@extends('layout')

@section('title', 'Asistencia | ' . $asistencia->alumno->alum_nombre)

@section('content')

@include('partials.estado')

<div class="asist-detail-container">
    <div class="asist-detail-header">
        <h1 class="asist-detail-title">Detalle de Asistencia</h1>
        @if($asistencia && $asistencia->created_at)
            <span class="asist-detail-timestamp">Registrado {{ $asistencia->created_at->diffForHumans() }}</span>
        @endif
    </div>

    <div class="asist-detail-card">

        <div class="asist-detail-info-row">
            <div class="asist-detail-label"><i class="fa-solid fa-user"></i> Nombre del Alumno:</div>
            <div class="asist-detail-value">
                <span class="asist-detail-highlight">{{ $asistencia->alumno->alum_nombre ?? 'No disponible' }} {{ $asistencia->alumno->alum_apellido ?? '' }}</span>
            </div>
        </div>

        @if(auth()->user()->is(\App\Models\User::ROL_ADMIN) || auth()->user()->is(App\Models\User::ROL_EMPLEADO) )
        <div class="asist-detail-info-row">
            <div class="asist-detail-label"><i class="fa-solid fa-id-card"></i> Membresía Principal:</div>
            <div class="asist-detail-value">
                @if($membresiaPrincipal)
                    {{ $membresiaPrincipal->membresia->mem_nomb }}
                @else
                    <span class="asist-detail-warning">Sin membresía principal registrada</span>
                @endif
            </div>
        </div>
        @endif

        <div class="asist-detail-info-row">
            <div class="asist-detail-label"><i class="fa-solid fa-calendar"></i> Fecha de Vencimiento:</div>
            <div class="asist-detail-value">
                @if($membresiaPrincipal)
                    @php
                        $fechaFin = \Carbon\Carbon::parse($membresiaPrincipal->pag_fin);
                        $hoy = now();
                        $diasRestantes = $hoy->diffInDays($fechaFin, false);

                        if ($diasRestantes < 0) {
                            $mensajeVencimiento = '<span class="status status-expired">Vencida</span>';
                        } elseif ($diasRestantes <= 5) {
                            $mensajeVencimiento = '<span class="status status-expiring">Por caducar / Renovar (' . $diasRestantes . ' días restantes)</span>';
                        } else {
                            $mensajeVencimiento = '<span class="status status-active">' . $fechaFin->format('d/m/Y') . '</span>';
                        }
                    @endphp
                    {!! $mensajeVencimiento !!}
                @else
                    <span class="asist-detail-warning">Sin fecha de vencimiento</span>
                @endif
            </div>
        </div>


        <div class="asist-detail-info-row">
            <div class="asist-detail-label"><i class="fa-solid fa-dollar-sign"></i> Estado del Pago:</div>
            <div class="asist-detail-value">
                @if($membresiaPrincipal)
                    @php
                        $estadoPago = $membresiaPrincipal->estado_pago;
                        $claseEstadoPago = $estadoPago === 'completo' ? 'status-active' : 'status-expiring';
                    @endphp
                    <span class="status {{ $claseEstadoPago }}">{{ ucfirst($estadoPago) }}</span>
                @else
                    <span class="asist-detail-warning">Sin estado de pago registrado</span>
                @endif
            </div>
        </div>


        <div class="asist-detail-info-row">
            <div class="asist-detail-label"><i class="fa-solid fa-user-check"></i> Usuario de Registro:</div>
            <div class="asist-detail-value">{{ $asistencia->user->name ?? 'No disponible' }}</div>
        </div>


        <div class="asist-detail-info-row">
            <div class="asist-detail-label"><i class="fa-solid fa-calendar-check"></i> Fecha de Asistencia:</div>
            <div class="asist-detail-value">{{ $asistencia->visi_fecha }}</div>
        </div>


        <div class="asist-detail-info-row">
            <div class="asist-detail-label"><i class="fa-solid fa-building"></i> Lugar de Registro:</div>
            <div class="asist-detail-value">{{ $asistencia->sede->sede_direccion ?? 'No disponible' }}</div>
        </div>
    </div>

    <div class="asist-detail-actions">
        <a href="{{ route('asistencia.index') }}" class="asist-detail-button asist-detail-button-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver a la lista
        </a>

        @if (auth()->user()->is(\App\Models\User::ROL_ADMIN))
        {{-- ||auth()->user()->is(\App\Models\User::ROL_EMPLEADO) --}}
            <a href="{{ route('asistencia.edit', $asistencia) }}" class="asist-detail-button asist-detail-button-primary">
                <i class="fa-solid fa-pen-to-square"></i> Editar Asistencia
            </a>
        @endif
        {{-- ||auth()->user()->is(\App\Models\User::ROL_EMPLEADO) --}}
            <a href="{{ route('asistencia.create') }}" class="asist-detail-button asist-detail-button-primary">
                <i class="fa-solid fa-plus"></i> Crear Asistencia
            </a>

    </div>
</div>
@endsection
