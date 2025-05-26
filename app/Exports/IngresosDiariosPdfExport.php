<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class IngresosDiariosPdfExport
{
    protected $fechaInicio;
    protected $sedeId;

    public function __construct($fechaInicio, $sedeId)
    {
        $this->fechaInicio = $fechaInicio;
        $this->sedeId = $sedeId;
    }

    public function generarPdf()
    {
        // Definir el rango de tiempo para el día completo
        $fechaInicioDia = $this->fechaInicio . ' 00:00:00';
        $fechaFinDia = $this->fechaInicio . ' 23:59:59';

        // Consulta principal: Detalles de pagos
        $pagos = DB::table('pago_detalles as pd')
            ->join('pagos as p', 'pd.fkpago', '=', 'p.id_pag')
            ->join('metodos_pago as mp', 'pd.fkmetodo', '=', 'mp.id_metod')
            ->join('membresias as m', 'pd.fkmemb', '=', 'm.id_mem')
            ->select(
                DB::raw("mp.tipo_pago as metodo_pago"),
                DB::raw("m.mem_nomb as membresia"),
                DB::raw("m.mem_durac as duracion"),
                DB::raw("COUNT(*) as cantidad_pagos"),
                DB::raw("SUM(CASE WHEN pd.estado = 'completo' THEN 1 ELSE 0 END) as pagos_completos"),
                DB::raw("SUM(CASE WHEN pd.estado = 'incompleto' THEN 1 ELSE 0 END) as pagos_incompletos"),
                DB::raw("SUM(pd.monto) as monto_total")
            )
            ->where('p.fksede', $this->sedeId)
            ->whereBetween('pd.created_at', [$fechaInicioDia, $fechaFinDia])
            ->groupBy('mp.tipo_pago', 'm.mem_nomb', 'm.mem_durac')
            ->get();

        // Total de pagos
        $totalPagos = DB::table('pago_detalles as pd')
            ->join('pagos as p', 'pd.fkpago', '=', 'p.id_pag')
            ->where('p.fksede', $this->sedeId)
            ->whereBetween('pd.created_at', [$fechaInicioDia, $fechaFinDia])
            ->sum('pd.monto');

        $subtotalesPorMetodoPago = DB::table('pago_detalles as pd')
            ->join('pagos as p', 'pd.fkpago', '=', 'p.id_pag')
            ->join('metodos_pago as mp', 'pd.fkmetodo', '=', 'mp.id_metod')
            ->select(
                'mp.tipo_pago as metodo_pago',
                DB::raw("SUM(pd.monto) as monto_total"),
                DB::raw("COUNT(*) as cantidad_pagos")
            )
            ->where('p.fksede', $this->sedeId)
            ->whereBetween('pd.created_at', [$fechaInicioDia, $fechaFinDia])
            ->groupBy('mp.tipo_pago')
            ->get()
            ->keyBy('metodo_pago');
        // Consulta de ventas
        $ventas = DB::table('ventas as v')
            ->join('metodos_pago as mp', 'v.fkmetodo', '=', 'mp.id_metod')
            ->select(
                DB::raw("mp.tipo_pago as metodo_pago"),
                DB::raw("COUNT(*) as total_ventas"),
                DB::raw("SUM(v.venta_total) as monto_total")
            )
            ->where('v.fksede', $this->sedeId)
            ->whereBetween('v.created_at', [$fechaInicioDia, $fechaFinDia])
            ->groupBy('mp.tipo_pago')
            ->get();

        // Total de ventas
        $totalVentas = DB::table('ventas as v')
            ->where('v.fksede', $this->sedeId)
            ->whereBetween('v.created_at', [$fechaInicioDia, $fechaFinDia])
            ->sum('v.venta_total');

        // Productos vendidos
        $productosVendidos = DB::table('detalle_venta as dv')
            ->join('productos as p', 'dv.fkproducto', '=', 'p.id_productos')
            ->select(
                'p.prod_nombre as producto',
                DB::raw('SUM(dv.datelle_cantidad) as cantidad_vendida'),
                DB::raw('SUM(dv.datelle_sub_total) as monto_total_producto')
            )
            ->join('ventas as v', 'dv.fkventa', '=', 'v.id_venta')
            ->where('v.fksede', $this->sedeId)
            ->whereBetween('v.created_at', [$fechaInicioDia, $fechaFinDia])
            ->groupBy('p.prod_nombre')
            ->get();

        // Consulta de gastos
        $gastos = DB::table('gastos as g')
            ->select(
                'g.gast_categoria as categoria',
                'g.gast_descripcion as descripcion',
                DB::raw('SUM(g.gast_monto) as monto_total')
            )
            ->where('g.fksede', $this->sedeId)
            ->whereDate('g.created_at', $this->fechaInicio)
            ->groupBy('g.gast_categoria', 'g.gast_descripcion')
            ->get();

        // Total de gastos
        $totalGastos = DB::table('gastos')
            ->where('fksede', $this->sedeId)
            ->whereDate('created_at', $this->fechaInicio)
            ->sum('gast_monto');

        // Cálculos adicionales
        $totalIngresos = $totalPagos + $totalVentas;
        $balanceNeto = $totalIngresos - $totalGastos;

        $fechaReporte = \Carbon\Carbon::parse($this->fechaInicio)->format('d/m/Y');
        $sede = DB::table('sedes')->where('id_sede', $this->sedeId)->value('sede_nombre');

        $data = [
            'sede' => $sede,
            'fechaReporte' => $fechaReporte,
            'pagos' => $pagos,
            'ventas' => $ventas,
            'productosVendidos' => $productosVendidos,
            'gastos' => $gastos,
            'totalPagos' => $totalPagos,
            'totalVentas' => $totalVentas,
            'totalGastos' => $totalGastos,
            'totalIngresos' => $totalIngresos,
            'balanceNeto' => $balanceNeto,
            'subtotalesPorMetodoPago' => $subtotalesPorMetodoPago ?? [],
            'subtotalesVentas' => $subtotalesVentas ?? [],
        ];

        $pdf = Pdf::loadView('reporte.ingresos_diarios_pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20)
            ->setOption('margin-left', 20)
            ->setOption('margin-right', 20);

        return $pdf->stream('ingresos_diarios.pdf');
    }
}
