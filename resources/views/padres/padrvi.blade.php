@extends('layout')

@section('title','Padre')

@section('content')
@include('partials.estado')

<div class="padres-container">
    <div class="padres-header">
        <h1 class="padres-title"><i class="fa-solid fa-users"></i> Lista de Padres</h1>
        <div class="padres-actions">
            <a href="{{ route('alumno.index') }}" class="padres-btn padres-btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver a Alumnos
            </a>
            {{-- <a href="{{ route('padres.create') }}" class="padres-btn padres-btn-primary">
                <i class="fa-solid fa-user-plus"></i> Nuevo Padre
            </a> --}}
        </div>
    </div>

    <div class="padres-table-container">
        @if(count($padres) > 0)
            <table class="padres-table">
                <thead class="padres-table-head">
                    <tr>
                        <th class="padres-table-th">Nombre</th>
                        <th class="padres-table-th">Apellido</th>
                        <th class="padres-table-th">Teléfono</th>
                        <th class="padres-table-th">Correo</th>
                        <th class="padres-table-th">Alumno Asociado</th>
                        <th class="padres-table-th padres-table-actions">Acciones</th>
                    </tr>
                </thead>
                <tbody class="padres-table-body">
                    @foreach ($padres as $padre)
                        <tr class="padres-table-row">
                            <td class="padres-table-td">{{ $padre->padre_nombre }}</td>
                            <td class="padres-table-td">{{ $padre->padre_apellido }}</td>
                            <td class="padres-table-td">
                                <a href="tel:{{ $padre->padre_telefono }}" class="padres-phone-link">
                                    <i class="fa-solid fa-phone"></i> {{ $padre->padre_telefono }}
                                </a>
                            </td>
                            <td class="padres-table-td">
                                @if($padre->padre_correo)
                                    <a href="mailto:{{ $padre->padre_correo }}" class="padres-email-link">
                                        <i class="fa-solid fa-envelope"></i> {{ $padre->padre_correo }}
                                    </a>
                                @else
                                    <span class="padres-no-data">No especificado</span>
                                @endif
                            </td>
                            <td class="padres-table-td">
                                <a href="{{ route('alumno.show', $padre->alumno->id_alumno) }}" class="padres-alumno-link">
                                    {{ $padre->alumno->alum_nombre }} {{ $padre->alumno->alum_apellido }}
                                </a>
                            </td>
                            <td class="padres-table-td padres-table-actions">
                                <div class="padres-action-buttons">
                                    <a href="{{ route('padres.edit', $padre->id_padre) }}" class="padres-btn-icon padres-btn-edit" title="Editar padre">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('padres.destroy', $padre->id_padre) }}" method="POST" class="padres-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="padres-btn-icon padres-btn-delete" title="Eliminar padre" onclick="return confirm('¿Estás seguro de eliminar este padre?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="padres-empty">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p>No hay padres registrados</p>
{{--                <a href="{{ route('padres.create') }}" class="padres-btn padres-btn-primary">--}}
{{--                    <i class="fa-solid fa-user-plus"></i> Registrar Padre--}}
{{--                </a>--}}
            </div>
        @endif
    </div>
</div>
@endsection
