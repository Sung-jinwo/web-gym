<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ingresos Diarios por Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .report-container {
            width: 100%;
            padding: 20px;
        }
        .report-title {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 8px;
            border-left: 4px solid #6c757d;
            margin-bottom: 15px;
        }
        .user-section {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .user-header {
            background-color: #343a40;
            color: white;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 15px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th, .data-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-muted {
            color: #6c757d;
        }
        .incomplete-payment {
            color: #dc3545;
            font-weight: bold;
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
            margin-bottom: 20px;
        }
        .summary-table th {
            background-color: #343a40;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .summary-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        .positive {
            color: #28a745;
        }
        .negative {
            color: #dc3545;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .user-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .user-summary-item {
            flex: 1;
            min-width: 150px;
            margin: 5px;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .user-summary-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="report-container">
    <h1 class="report-title">Reporte Financiero Diario por Usuario</h1>

    <!-- Encabezado -->
    <div class="text-center text-muted" style="margin-bottom: 20px;">
        <p><strong>Sede:</strong> {{ $sede ?? 'No especificada' }}</p>
        <p><strong>Fecha:</strong> {{ $fechaReporte ?? 'No especificada' }}</p>
    </div>

    <!-- Tabla Resumen Financiero General -->
    <div class="section">
        <h2 class="section-title">Resumen Financiero General</h2>
        <table class="summary-table">
            <thead>
            <tr>
                <th>Concepto</th>
                <th class="text-right">Monto</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><strong>Total Pagos (Membresías)</strong></td>
                <td class="text-right total-cell">${{ number_format($totalPagos, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Ventas (Productos)</strong></td>
                <td class="text-right total-cell">${{ number_format($totalVentas, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Ingresos</strong></td>
                <td class="text-right total-cell">${{ number_format($totalIngresos, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Gastos</strong></td>
                <td class="text-right expense-cell">${{ number_format($totalGastos, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Balance Neto</strong></td>
                <td class="text-right {{ $balanceNeto >= 0 ? 'total-cell' : 'expense-cell' }}">
                    ${{ number_format($balanceNeto, 2) }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Sección por Usuario -->
    @foreach($datosPorUsuario as $datosUsuario)
        <div class="user-section">
            <div class="user-header">
                <h2 style="margin: 0; color: white;">Usuario: {{ $datosUsuario['usuario']->name }}</h2>
            </div>

            <!-- Resumen por usuario -->
            <div class="user-summary">
                <div class="user-summary-item">
                    <div class="user-summary-title">Total Pagos</div>
                    <div class="text-right total-cell">${{ number_format($datosUsuario['totalPagos'], 2) }}</div>
                </div>
                <div class="user-summary-item">
                    <div class="user-summary-title">Total Ventas</div>
                    <div class="text-right total-cell">${{ number_format($datosUsuario['totalVentas'], 2) }}</div>
                </div>
                <div class="user-summary-item">
                    <div class="user-summary-title">Total Ingresos</div>
                    <div class="text-right total-cell">${{ number_format($datosUsuario['totalIngresos'], 2) }}</div>
                </div>
                <div class="user-summary-item">
                    <div class="user-summary-title">Total Gastos</div>
                    <div class="text-right expense-cell">${{ number_format($datosUsuario['totalGastos'], 2) }}</div>
                </div>
                <div class="user-summary-item">
                    <div class="user-summary-title">Balance Neto</div>
                    <div class="text-right {{ $datosUsuario['balanceNeto'] >= 0 ? 'total-cell' : 'expense-cell' }}">
                        ${{ number_format($datosUsuario['balanceNeto'], 2) }}
                    </div>
                </div>
            </div>

            <!-- Sección de Pagos del Usuario -->
            <div class="section">
                <h3 class="section-title">Pagos Generados</h3>
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Método de Pago</th>
                        <th>Membresía</th>
                        <th>Duración (Días)</th>
                        <th>Cantidad</th>
                        <th>Completos</th>
                        <th>Incompletos</th>
                        <th class="text-right">Monto Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($datosUsuario['pagos']) > 0)
                        @foreach ($datosUsuario['pagos'] as $pago)
                            <tr>
                                <td>{{ $pago->metodo_pago }}</td>
                                <td>{{ $pago->membresia }}</td>
                                <td>{{ $pago->duracion }}</td>
                                <td>{{ $pago->cantidad_pagos }}</td>
                                <td>{{ $pago->pagos_completos }}</td>
                                <td class="{{ $pago->pagos_incompletos > 0 ? 'incomplete-payment' : '' }}">
                                    {{ $pago->pagos_incompletos }}
                                </td>
                                <td class="text-right">${{ number_format($pago->monto_total, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay registros de pagos</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Detalle de Métodos de Pago del Usuario -->
            <div class="section">
                <h3 class="section-title">Detalle de Métodos de Pago</h3>
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Método de Pago</th>
                        <th>Cantidad de Transacciones</th>
                        <th class="text-right">Monto Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($datosUsuario['metodosPago']) > 0)
                        @foreach ($datosUsuario['metodosPago'] as $metodo)
                            <tr>
                                <td>{{ $metodo->metodo_pago }}</td>
                                <td>{{ $metodo->cantidad_pagos }}</td>
                                <td class="text-right">${{ number_format($metodo->monto_total, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay datos por método de pago</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Sección de Ventas del Usuario -->
            <!-- Sección de Ventas basadas en detalle_venta -->
            <div class="section">
                <h3 class="section-title">Ventas Generadas (Detalle)</h3>
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Método de Pago</th>
                        <th>N° de Ventas</th>
                        <th class="text-right">Monto Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($datosUsuario['ventasDetalle']) > 0)
                        @foreach ($datosUsuario['ventasDetalle'] as $venta)
                            <tr>
                                <td>{{ $venta->metodo_pago }}</td>
                                <td>{{ $venta->total_ventas }}</td>
                                <td class="text-right">${{ number_format($venta->monto_total, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay registros de ventas</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Sección de Productos Vendidos por el Usuario -->
            <div class="section">
                <h3 class="section-title">Productos Vendidos</h3>
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad Vendida</th>
                        <th class="text-right">Monto Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($datosUsuario['productosVendidos']) > 0)
                        @foreach ($datosUsuario['productosVendidos'] as $producto)
                            <tr>
                                <td>{{ $producto->producto }}</td>
                                <td>{{ $producto->cantidad_vendida }}</td>
                                <td class="text-right">${{ number_format($producto->monto_total_producto, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay productos vendidos</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Sección de Gastos del Usuario -->
            <div class="section">
                <h3 class="section-title">Gastos Realizados</h3>
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th class="text-right">Monto</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($datosUsuario['gastos']) > 0)
                        @foreach ($datosUsuario['gastos'] as $gasto)
                            <tr>
                                <td>{{ $gasto->categoria }}</td>
                                <td>{{ $gasto->descripcion }}</td>
                                <td class="text-right expense-cell">${{ number_format($gasto->monto_total, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay registros de gastos</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
</body>
</html>