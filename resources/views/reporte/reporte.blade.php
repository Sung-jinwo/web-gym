@extends('layout')

@section('title', 'Generar Reportes')

@section('content')
    @include('partials.validation-errors')

    <div class="categoria-form-unique-container">
        <h1>Generar Reportes</h1>

        <form action="{{ route('reporte.generar') }}" method="GET" class="categoria-form-unique-form">
            @csrf
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="tipo_reporte" class="form-label">
                        <i class="fa-icon fa-solid fa-file"></i> Tipo de Reporte
                    </label>
                    <select class="form-select" id="tipo_reporte" name="tipo_reporte" required>
                        <option value="">Seleccione un Reporte</option>
                        <option value="alumnos">Alumnos</option>
                         @if(auth()->user()->is(App\Models\User::ROL_ADMIN))
                        {{-- <option value="pagos">Pagos</option> --}}
                        <option value="asistencias">Asistencias</option>
                        {{-- <option value="ventas">Ventas</option> --}}
                        <option value="inventario">Inventario</option>
                        <option value="pagos_ventas">Pagos y Ventas (Excel)</option>
                        <option value="ingresos_diarios">Ingresos Diarios (PDF)</option>
                        @endif
                    </select>
                </div>
                <br>
                <div class="col-md-4">
                    <label for="sede_id" class="form-label">
                        <i class="fa-icon fa-solid fa-building"></i> Sede
                    </label>
                    @if(auth()->user()->is(App\Models\User::ROL_ADMIN)|| auth()->user()->is(App\Models\User::ROL_VENTAS))
                    <select class="form-select" id="sede_id" name="sede_id" required>
                        <option value="">Seleccione una sede</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id_sede }}">{{ $sede->sede_nombre }}</option>
                        @endforeach
                    </select>
                    @else

                     <input type="hidden" name="sede_id" id="sede_id"  value="{{ auth()->user()->fksede }}">
                     <select class="form-select" disabled>
                            <option >{{ auth()->user()->sede->sede_nombre }}</option>

                    </select>
                    @endif
                </div>
            </div>

            <!-- Sección de fecha/mes -->
            <div class="row mb-3" id="fecha-container">
                <div class="col-md-4">
                    <label for="fecha" class="form-label" id="fecha-label">
                        <i class="fa-icon fa-solid fa-calendar"></i>
                        <span id="fecha-text">Fecha</span>
                    </label>
                    <input type="date" class="form-control" id="fecha" name="fecha">
                    <input type="month" class="form-control" id="mes" name="mes" style="display: none;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="icono-guardar"></i> Generar Reporte
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoReporte = document.getElementById('tipo_reporte');
            const fechaContainer = document.getElementById('fecha-container');
            const fechaInput = document.getElementById('fecha');
            const mesInput = document.getElementById('mes');
            const fechaLabel = document.getElementById('fecha-label');
            const fechaText = document.getElementById('fecha-text');

            function actualizarCamposFecha() {
                const reporte = tipoReporte.value;

                // Ocultar para alumnos e inventario
                if (reporte === 'alumnos' || reporte === 'inventario') {
                    fechaContainer.style.display = 'none';
                    fechaInput.removeAttribute('required');
                    mesInput.removeAttribute('required');
                } else {
                    fechaContainer.style.display = 'flex';

                    // Configurar para ingresos diarios (fecha específica)
                    if (reporte === 'ingresos_diarios') {
                        fechaText.textContent = 'Fecha (Día)';
                        fechaInput.style.display = 'block';
                        mesInput.style.display = 'none';
                        fechaInput.setAttribute('required', 'true');
                        mesInput.removeAttribute('required');
                    }
                    // Configurar para pagos, ventas y pagos_ventas (mes)
                    else if (['pagos', 'ventas', 'pagos_ventas', 'asistencias'].includes(reporte)) {
                        fechaText.textContent = 'Mes';
                        fechaInput.style.display = 'none';
                        mesInput.style.display = 'block';
                        mesInput.setAttribute('required', 'true');
                        fechaInput.removeAttribute('required');
                    }
                }
            }

            tipoReporte.addEventListener('change', actualizarCamposFecha);

            // Inicializar estado
            actualizarCamposFecha();
        });
    </script>
@endsection
