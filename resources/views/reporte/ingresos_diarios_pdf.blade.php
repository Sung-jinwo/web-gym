<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ingresos Diarios</title>
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
    </style>
</head>
<body>
<div class="report-container">
    <h1 class="report-title">Reporte Financiero Diario</h1>

    <!-- Encabezado -->
    <div class="text-center text-muted" style="margin-bottom: 20px;">
        <p><strong>Sede:</strong> {{ $sede ?? 'No especificada' }}</p>
        <p><strong>Fecha:</strong> {{ $fechaReporte ?? 'No especificada' }}</p>
    </div>

    <!-- Tabla Resumen Financiero -->
    <div class="section">
        <h2 class="section-title">Resumen Financiero</h2>
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

    <!-- Sección de Pagos -->
    <div class="section">
        <h2 class="section-title">Detalle de Pagos</h2>
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
            @if(count($pagos) > 0)
                @foreach ($pagos as $pago)
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

    <div class="section">
        <h2 class="section-title">Resumen por Método de Pago</h2>
        <table class="data-table">
            <thead>
            <tr>
                <th>Método de Pago</th>
                <th>Cantidad de Transacciones</th>
                <th class="text-right">Monto Total</th>
            </tr>
            </thead>
            <tbody>
            @if(count($subtotalesPorMetodoPago) > 0)
                @foreach ($subtotalesPorMetodoPago as $metodo => $detalle)
                    <tr>
                        <td>{{ $metodo }}</td>
                        <td>{{ $detalle->cantidad_pagos }}</td>
                        <td class="text-right">${{ number_format($detalle->monto_total, 2) }}</td>
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

    <!-- Sección de Ventas -->
    <div class="section">
        <h2 class="section-title">Detalle de Ventas</h2>
        <table class="data-table">
            <thead>
            <tr>
                <th>Método de Pago</th>
                <th>Cantidad de Ventas</th>
                <th class="text-right">Monto Total</th>
            </tr>
            </thead>
            <tbody>
            @if(count($ventas) > 0)
                @foreach ($ventas as $venta)
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

    <!-- Sección de Productos Vendidos -->
    <div class="section">
        <h2 class="section-title">Productos Vendidos</h2>
        <table class="data-table">
            <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad Vendida</th>
                <th class="text-right">Monto Total</th>
            </tr>
            </thead>
            <tbody>
            @if(count($productosVendidos) > 0)
                @foreach ($productosVendidos as $producto)
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

    <!-- Sección de Gastos -->
    <div class="section">
        <h2 class="section-title">Detalle de Gastos</h2>
        <table class="data-table">
            <thead>
            <tr>
                <th>Categoría</th>
                <th>Descripción</th>
                <th class="text-right">Monto</th>
            </tr>
            </thead>
            <tbody>
            @if(count($gastos) > 0)
                @foreach ($gastos as $gasto)
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
</body>
</html>
