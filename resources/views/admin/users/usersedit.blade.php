@extends('layout')

@section('title', 'Editar usuario')

@section('content')
@include('partials.estado')

<h1 >Editar usuario</h1>
<form  action="" method="POST">
    @csrf
    <div class="form-section">
        <div class="section-header">
            <h3 class="Sub_titulos"><i class="fa-solid fa-user-lock"></i> Actualizar Contraseña del Usuario</h3>
            <p class="section-description">Verifica la identidad del usuario y asigna una nueva contraseña segura.</p>
        </div>

        <div class="section-content">
            <div class="filter-row">
                <div class="filter-item">
                    <label for="name" class="filter-label">
                        <i class="fa-solid fa-user"></i> Nombre
                    </label>
                    <input type="text" id="name" class="filter-dropdown" value="{{ $user->name }}" readonly placeholder="Nombre del usuario">
                </div>

                <div class="filter-item">
                    <label for="email" class="filter-label">
                        <i class="fa-solid fa-envelope"></i> Email
                    </label>
                    <input type="text" id="email" class="filter-dropdown" value="{{ $user->email }}" readonly placeholder="Correo electrónico del usuario">
                </div>
            </div>

            <div class="filter-row">
                <div class="filter-item">
                    <label for="password" class="filter-label">
                        <i class="fa-solid fa-key"></i> Nueva Contraseña
                    </label>
                    <input type="password" name="password" id="password" class="filter-dropdown"
                           required placeholder="Ingrese una nueva contraseña">
                    @if($errors->has('password'))
                        <span class="error-message">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="filter-item">

                </div>
            </div>
        </div>
    </div>


    <div class="form-actions-enhanced">
        <a href="{{route('admin.users.index')}}" class="btn btn-secondary">
            <i class="icono-volver"></i> Lista de Usuarios
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="icono-guardar"></i>
            Guardar cambios
        </button>
    </div>
</form>

@endsection
