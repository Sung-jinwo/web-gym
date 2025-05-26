@extends('layout')

@section('title','Usuarios')

@section('content')
    <div class="padres-container">
        <div class="padres-header">
            <h1 class="padres-title"><i class="fa-solid fa-users"></i> Lista de usuarios</h1>
        </div>
        @include('partials.estado')

        <div class="padres-table-container">
            <table class="padres-table">
                <thead class="padres-table-head">
                <tr>
                    <th class="padres-table-th">Nombre</th>
                    <th class="padres-table-th">Email</th>
                    <th class="padres-table-th">Sede</th>
                    <th class="padres-table-th">Rol</th>
                    <th class="padres-table-th padres-table-actions">Acciones</th>
                </tr>
                </thead>
                <tbody class="padres-table-body">
                @foreach ($users as $user)
                    <tr class="padres-table-row">
                        <td class="padres-table-td">{{ $user->name }}</td>
                        <td class="padres-table-td">{{ $user->email }}</td>
                        <td class="padres-table-td">
                            @if ($user->fksede)
                               {{ $user->fksede ? $user->sede->sede_nombre : '' }}
                            @else
                                No tiene Sede
                            @endif
                        </td>
                        <td class="padres-table-td">
                            {{ $user->nombreRol }}
                        </td>
                        <td class="padres-table-td padres-table-actions">
                            <div class="padres-action-buttons">
                                <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}" class="padres-btn-icon padres-btn-edit" title="Editar usuario">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
