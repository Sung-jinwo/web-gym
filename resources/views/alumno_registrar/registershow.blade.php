@extends('layout')

@section('title', 'Prospecto | ' . ($registros ? $registros->alum_apellido : 'No encontrado'))

@section('content')
@include('partials.estado')


<div class="info-card">
    <div class="info-card__image">
        @if ($registros->alum_img)
            <img src="{{ asset('storage/' . $registros->alum_img) }}" alt="Vista previa de {{ $registros->alum_nombre }} {{ $registros->Alumno->alum_apellido }}">
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
                <i class="fa-solid fa-user-check"></i> {{ $registros->alum_nombre }} {{ $registros->alum_apellido }}
            </h1>
            <div class="info-card__status">
                    <span class="status @if($registros->alum_estado == 'A') status-active @else status-inactive @endif">
                        <i class="fa-solid fa-circle"></i> @if($registros->alum_estado == 'A') Activo @else Inactivo @endif
                    </span>
            </div>
        </div>
        <div class="info-card__grid">

            <div class="info-card__grid-item">
                <span class="info-card__label"><i class="fa-solid fa-user"></i> Sexo:</span>
                <span class="info-card__value">@if ($registros->fksede == 1) Masculino @else Femenino @endif</span>
            </div>
            <div class="info-card__grid-item">
                <span class="info-card__label"><i class="fa-solid fa-calendar"></i> Edad:</span>
                <span class="info-card__value">{{ $registros->alum_eda }} años</span>
            </div>
            <div class="info-card__grid-item">
                <span class="info-card__label"><i class="fa-solid fa-phone"></i> Teléfono:</span>
                <span class="info-card__value">{{ $registros->alum_telefo }}</span>
            </div>
            <div class="info-card__grid-item">
                <span class="info-card__label"><i class="fa-solid fa-building"></i> Sede:</span>
                <span class="info-card__value">{{ $registros->sede->sede_nombre }}</span>
            </div>
        </div>
        <div class="info-card__actions">
            <a href="{{ route('registro.index') }}" class="btn-icon btn-blue" title="Volver al listado">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <a href="{{ route('registro.edit', $registros) }}" class="btn-icon btn-green" title="Editar alumno">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('registro.estado', $registros) }}" method="POST" class="inline-form">
                @csrf
                @method('PUT')
                @if ($registros->alum_estado == 'A')
                    <button type="submit" class="btn-icon btn-red" title="Inhabilitar alumno" onclick="return confirm('¿Estás seguro de desactivar este alumno?')">
                        <i class="fa-solid fa-times-circle"></i>
                    </button>
                @elseif($registros->alum_estado == 'E')
                    <button type="submit" class="btn-icon btn-green" title="Reactivar alumno" onclick="return confirm('¿Estás seguro de Activar este alumno?')">
                        <i class="fa-solid fa-check-circle"></i>
                    </button>
                @endif
            </form>
            <form action="{{ route('alumno.destroy', $registros) }}" method="POST" class="inline-form">
                @csrf @method('DELETE')
                @if ($registros->alum_estado == 'E')
                    <button type="submit" class="btn-icon btn-red" title="Eliminar alumno" onclick="return confirm('¿Estás seguro de eliminar al alumno?')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>



@endsection
