<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('icon/icongym.png') }}">
    <link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/memb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/asis.css') }}">
    <link rel="stylesheet" href="{{ asset('css/producto.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pago.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reporte.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/regis.css') }}">
    <link rel="stylesheet" href="{{ asset('css/botones.css') }}">
    <link rel="stylesheet" href="{{ asset('css/iconos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/formulario.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/graficos.css') }}">
    <script  src="{{ asset('js/sidebar.js') }}" ></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
{{--    <script src="https://unpkg.com/feather-icons"></script>--}}

</head>
<body>
    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay para móvil -->
    <div class="mobile-overlay" onclick="closeMobileMenu()"></div>

    <nav class="sidebar">
        <button class="menu-toggle" onclick="toggleMenu()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="sidebar__header">
            <div class="sidebar__logo-container">
                <img src="{{ asset('icon/icongym.png') }}" alt="Logo" class="sidebar__logo">
                <h1 class="sidebar__title">IVONNE GYM</h1>
                <p class="sidebar__user-info">
                    @auth
                    @if(auth()->user()->is(App\Models\User::ROL_ADMIN))
                        <i class="fas fa-shield-alt"></i> Administrador
                    @elseif(auth()->user()->is(App\Models\User::ROL_ASISTENCIA))
                        <i class="fas fa-calendar-check"></i> Asistencia |
                        @if(auth()->user()->sede)
                            {{ auth()->user()->sede->sede_nombre }}
                        @else
                            Sin sede asignada
                        @endif
                    @elseif(auth()->user()->is(App\Models\User::ROL_EMPLEADO))
                        <i class="fas fa-user-tie"></i> Empleado |
                        @if(auth()->user()->sede)
                            {{ auth()->user()->sede->sede_nombre }}
                        @else
                            Sin sede asignada
                        @endif
                    @elseif(auth()->user()->is(App\Models\User::ROL_VENTAS))
                        <i class="fas fa-user"></i> Ventas |
                        @if(auth()->user()->name)
                            {{ auth()->user()->name }}
                        @else
                            Sin sede asignada
                        @endif
                    @endif
                @endauth
                
                </p>
              </div>


        </div>
        @include('partials.nav')

    </nav>



    <div class="main-content">
        @yield('content')

    </div>

    @yield('scripts')

{{--    <script>--}}
{{--    feather.replace();--}}
{{--  </script>--}}

</body>
</html>
