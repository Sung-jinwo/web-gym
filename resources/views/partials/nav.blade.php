<ul class="sidebar__nav">
    <li class="sidebar__item {{ setActivo('home') }}">
        <a href="/" class="sidebar__link">
            <i class="fa-solid fa-house"></i> <span class="sidebar__text">Home</span>
        </a>
    </li>

    @auth
        <!-- Mostrar para Admin y Empleado -->
        @if(auth()->user()->is(App\Models\User::ROL_ADMIN) || auth()->user()->is(App\Models\User::ROL_EMPLEADO)|| auth()->user()->is(App\Models\User::ROL_VENTAS))
            <li class="item sidebar__item--has-submenu {{ setActivo('alumno') }}">
                <a href="javascript:void(0)" class="sidebar__link sidebar__link--toggle">
                    <i class="fa-solid fa-users"></i>
                    <span class="sidebar__text">Alumnos</span>
                    <i class="fa-solid fa-chevron-down sidebar__arrow"></i>
                </a>
                <ul class="sidebar__submenu">
                    <li class="sidebar__submenu-item {{ request()->routeIs('alumno.index') ? 'active' : '' }}">
                        <a href="{{ route('alumno.index') }}" class="sidebar__submenu-link">
                            <i class="fa-solid fa-user-graduate"></i>
                            <span class="sidebar__text">Listado de Alumnos</span>
                        </a>
                    </li>
                    <li class="sidebar__submenu-item {{ request()->routeIs('registro.index') ? 'active' : '' }}">
                        <a href="{{ route('registro.index') }}" class="sidebar__submenu-link">
                            <i class="fa-solid fa-users-slash"></i>
                            <span class="sidebar__text">Prospecto Redes</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Mostrar para Admin, Empleado y Asistencia -->
        @if(auth()->user()->is(App\Models\User::ROL_ADMIN) ||
            auth()->user()->is(App\Models\User::ROL_EMPLEADO) ||
            auth()->user()->is(App\Models\User::ROL_ASISTENCIA)
            )
            <li class="sidebar__item {{ setActivo('asistencia') }}">
                <a href="{{
                    auth()->user()->is(App\Models\User::ROL_ASISTENCIA) ?
                    route('asistencia.create') :
                    route('asistencia.index')
                }}" class="sidebar__link">
                    <i class="fa-solid fa-calendar-check"></i> <span class="sidebar__text">Asistencia</span>
                </a>
            </li>
        @endif

        <!-- Mostrar solo para Admin y Empleado -->
        @if(auth()->user()->is(App\Models\User::ROL_ADMIN) || auth()->user()->is(App\Models\User::ROL_EMPLEADO)
            || auth()->user()->is(App\Models\User::ROL_VENTAS))
            <li class="sidebar__item {{ setActivo('membresias') }}">
                <a href="{{ route('membresias.index') }}" class="sidebar__link">
                    <i class="fa-solid fa-award"></i> <span class="sidebar__text">Membresías</span>
                </a>
            </li>

            <li class="sidebar__item {{ setActivo('producto') }}">
                <a href="{{ route('producto.index') }}" class="sidebar__link">
                    <i class="fa-solid fa-tshirt"></i> <span class="sidebar__text">Productos</span>
                </a>
            </li>

            <li class="item sidebar__item--has-submenu {{ setActivo('venta') }}">
                <a href="javascript:void(0)" class="sidebar__link sidebar__link--toggle">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span class="sidebar__text">Ventas</span>
                    <i class="fa-solid fa-chevron-down sidebar__arrow"></i>
                </a>
                <ul class="sidebar__submenu">
                    <li class="sidebar__submenu-item {{ request()->routeIs('venta.index') ? 'active' : '' }}">
                        <a href="{{ route('venta.index') }}" class="sidebar__submenu-link">
                            <i class="fa-solid fa-money-bill"></i>
                            <span class="sidebar__text">Ventas Generadas</span>
                        </a>
                    </li>
                    <li class="sidebar__submenu-item {{ request()->routeIs('venta.reservados') ? 'active' : '' }}">
                        <a href="{{ route('venta.reservados') }}" class="sidebar__submenu-link">
                            <i class="fa-solid fa-clock"></i>
                            <span class="sidebar__text">Listado de Reservados</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="item sidebar__item--has-submenu {{ setActivo('pagos') }}">
                <a href="javascript:void(0)" class="sidebar__link sidebar__link--toggle">
                    <i class="fa-solid fa-money-bill"></i>
                    <span class="sidebar__text">Pagos Completos</span>
                    <i class="fa-solid fa-chevron-down sidebar__arrow"></i>
                </a>
                <ul class="sidebar__submenu">
                    <li class="sidebar__submenu-item {{ request()->routeIs('pagos.completos') ? 'active' : '' }}">
                        <a href="{{ route('pagos.completos') }}" class="sidebar__submenu-link">
                            <i class="fa-solid fa-money-bill-wave"></i>
                            <span class="sidebar__text">Pagos Completos</span>
                        </a>
                    </li>
                    <li class="sidebar__submenu-item {{ request()->routeIs('pagos.incompletos') ? 'active' : '' }}">
                        <a href="{{ route('pagos.incompletos') }}" class="sidebar__submenu-link">
                            <i class="fa-solid fa-money-bill-1"></i>
                            <span class="sidebar__text">Pagos Incompletos</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar__item {{ setActivo('gasto') }}">
                <a href="{{ route('gasto.index') }}" class="sidebar__link">
                    <i class="fa-solid fa-calculator"></i> <span class="sidebar__text">Gastos</span>
                </a>
            </li>
        @endif

        <!-- Mostrar solo para Admin -->
        @if(auth()->user()->is(App\Models\User::ROL_ADMIN))

            <li class="item sidebar__item--has-submenu {{ setActivo('reportes') }}">
                <a href="javascript:void(0)" class="sidebar__link sidebar__link--toggle">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span class="sidebar__text">Reporte</span>
                    <i class="fa-solid fa-chevron-down sidebar__arrow"></i>
                </a>
                <ul class="sidebar__submenu">
                    <li class="sidebar__submenu-item {{ request()->routeIs('reportes.index') ? 'active' : '' }}">
                        <a href="{{ route('reportes.index') }}" class="sidebar__submenu-link">
                            <i class="fa-solid fa-money-bill"></i>
                            <span class="sidebar__text">Listado de pagos</span>
                        </a>
                    </li>
                    <li class="sidebar__submenu-item {{ request()->routeIs('reportes.ventas') ? 'active' : '' }}">
                        <a href="{{ route('reportes.ventas') }}" class="sidebar__submenu-link">
                            <i class="fa-solid fa-money-bill"></i>
                            <span class="sidebar__text">Listado de Ventas</span>
                        </a>
                    </li>
                    <li class="sidebar__submenu-item {{ request()->routeIs('reportes.formulario') ? 'active' : '' }}">
                        <a href="{{ route('reportes.formulario') }}" class="sidebar__submenu-link">
                            <i class="fa-solid fa-file-lines"></i>
                            <span class="sidebar__text">Generar Reportes</span>
                        </a>
                    </li>
                </ul>
            </li>


            <li class="sidebar__item  {{ setActivo('admin') }}">
                <a href="{{ route('admin.users.index') }}" class="sidebar__link" >
                    <i class="fa-solid fa-user"></i> <span class="sidebar__text">Usuario</span>
                </a>
            </li>
            <li class="sidebar__item  {{ setActivo('graficos') }}">
                <a href="{{route('graficos.index')}}" class="sidebar__link">
                    <i class="fa-solid fa-chart-column"></i> <span class="sidebar__text">Graficos</span>
                </a>
            </li>

        @endif

        <li class="sidebar__item sidebar__item--exit">
            <a class="sidebar__link" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> <span class="sidebar__text">Cerrar sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    @endauth

</ul>
