<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Resumen Financiero Diario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Reducir tamaño de fuente */
            margin: 0;
            padding: 0;
        }
        .report-container {
            width: 100%;
            padding: 10px; /* Reducir padding */
        }
        .report-title {
            text-align: center;
            color: #333;
            margin-bottom: 10px; /* Reducir margen */
            font-size: 14px; /* Título un poco más grande */
        }
        .section {
            margin-bottom: 15px; /* Reducir margen entre secciones */
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 5px; /* Reducir padding */
            border-left: 3px solid #6c757d;
            margin-bottom: 8px; /* Reducir margen */
            font-size: 11px; /* Tamaño de título de sección */
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px; /* Reducir margen */
            font-size: 20px; /* Tamaño de fuente más pequeño para tablas */
        }
        .data-table th, .data-table td {
            border: 1px solid #dee2e6;
            padding: 4px; /* Reducir padding */
            text-align: left;
        }
        .data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-muted {
            color: #6c757d;
            font-size: 9px;
        }
        .total-cell {
            color: #28a745;
            font-weight: bold;
        }
        .expense-cell {
            color: #dc3545;
            font-weight: bold;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px; /* Reducir margen */
            font-size: 9px; /* Tamaño de fuente más pequeño */
        }
        .summary-table th {
            background-color: #343a40;
            color: white;
            padding: 6px; /* Reducir padding */
            text-align: left;
            font-size: 9px;
        }
        .summary-table td {
            padding: 6px; /* Reducir padding */
            border: 1px solid #dee2e6;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .page-break {
            page-break-before: always;
        }
        .two-columns {
            display: flex;
            justify-content: space-between;
            gap: 20px; /* Espacio entre columnas */
        }
        .column {
            flex: 1;
            min-width: 0;
        }
        .compact-table {
            font-size: 8px; /* Tablas aún más compactas */
        }
        .compact-table th, 
        .compact-table td {
            padding: 3px; /* Padding más reducido */
        }
    </style>
</head>
<body>
<div class="report-container">
    <h1 class="report-title">Reporte Financiero Diario - Resumen General</h1>

    <!-- Encabezado -->
    <div class="text-center text-muted" style="margin-bottom: 10px;">
        <p style="margin: 2px 0;"><strong>Sede:</strong> {{ $sede ?? 'No especificada' }}</p>
        <p style="margin: 2px 0;"><strong>Fecha:</strong> {{ $fechaReporte ?? 'No especificada' }}</p>
    </div>

    <!-- Sección de dos columnas para optimizar espacio -->
    <div class="two-columns">
        <!-- Columna 1: Resumen Financiero General -->
        <div class="column">
            <div class="section">
                <h2 class="section-title">Resumen Financiero General</h2>
                <table class="summary-table compact-table">
                    <thead>
                    <tr>
                        <th>Concepto</th>
                        <th class="text-right">Monto</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Total Pagos (Detalle)</td>
                        <td class="text-right total-cell">${{ number_format($totalPagos, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Ventas (Detalle)</td>
                        <td class="text-right total-cell">${{ number_format($totalVentas, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Ingresos</td>
                        <td class="text-right total-cell">${{ number_format($totalIngresos, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Gastos</td>
                        <td class="text-right expense-cell">${{ number_format($totalGastos, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Balance Neto</td>
                        <td class="text-right {{ $balanceNeto >= 0 ? 'total-cell' : 'expense-cell' }}">
                            ${{ number_format($balanceNeto, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Total Comisiones</td>
                        <td class="text-right total-cell">${{ number_format($totalComisiones, 2) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Métodos de Pago Combinados -->
            <div class="section">
                <h2 class="section-title">Métodos de Pago</h2>
                <table class="data-table compact-table">
                    <thead>
                        <tr>
                            <th>Método</th>
                            <th class="text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($metodosPagoCombinados) > 0)
                            @foreach ($metodosPagoCombinados as $metodo => $monto)
                                <tr>
                                    <td>{{ $metodo }}</td>
                                    <td class="text-right">${{ number_format($monto, 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="text-center text-muted">No hay métodos de pago</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Columna 2: Comisiones por Asesor -->
        <div class="column">
            <div class="section">
                <h2 class="section-title">Comisiones por Asesor</h2>
                <table class="data-table compact-table">
                    <thead>
                        <tr>
                            <th>Asesor</th>
                            <th class="text-right">Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($comisionesPorUsuario) > 0)
                            @foreach ($comisionesPorUsuario as $comision)
                                <tr>
                                    <td>{{ $comision->asesor }}</td>
                                    <td class="text-right total-cell">${{ number_format($comision->total_comisiones, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td><strong>Total</strong></td>
                                <td class="text-right total-cell">${{ number_format($totalComisiones, 2) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2" class="text-center text-muted">No hay comisiones</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Información por usuario en páginas adicionales (si es necesario) -->
@if(isset($datosPorUsuario) && count($datosPorUsuario) > 0)
    <div class="page-break"></div>
    <div class="report-container">
        <h1 class="report-title">Detalle por Usuario - {{ $sede ?? 'No especificada' }}</h1>
        <p class="text-center text-muted"><strong>Fecha:</strong> {{ $fechaReporte ?? 'No especificada' }}</p>
        
        @foreach($datosPorUsuario as $datosUsuario)
            <div class="section">
                <h2 class="section-title">Usuario: {{ $datosUsuario['usuario']->name }}</h2>
                
                <!-- Resumen por usuario -->
                <table class="summary-table compact-table">
                    <tr>
                        <td>Total Pagos</td>
                        <td class="text-right total-cell">${{ number_format($datosUsuario['totalPagos'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Ventas</td>
                        <td class="text-right total-cell">${{ number_format($datosUsuario['totalVentas'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Ingresos</td>
                        <td class="text-right total-cell">${{ number_format($datosUsuario['totalIngresos'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Gastos</td>
                        <td class="text-right expense-cell">${{ number_format($datosUsuario['totalGastos'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Comisiones</td>
                        <td class="text-right total-cell">${{ number_format($datosUsuario['totalComisiones'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Balance Neto</td>
                        <td class="text-right {{ $datosUsuario['balanceNeto'] >= 0 ? 'total-cell' : 'expense-cell' }}">
                            ${{ number_format($datosUsuario['balanceNeto'], 2) }}
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
@endif
</body>
</html>