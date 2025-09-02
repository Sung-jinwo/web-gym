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

        // Obtener todos los usuarios activos en la sede
        $usuarios = DB::table('users as u')
        ->whereExists(function ($query) use ($fechaInicioDia, $fechaFinDia) {
            $query->select(DB::raw(1))
                  ->from('pagos as p')
                  ->whereColumn('p.fkuser', 'u.id')
                  ->where('p.fksede', $this->sedeId)
                  ->whereBetween('p.created_at', [$fechaInicioDia, $fechaFinDia]);
        })
        ->orWhereExists(function ($query) use ($fechaInicioDia, $fechaFinDia) {
            $query->select(DB::raw(1))
                  ->from('ventas as v')
                  ->whereColumn('v.fkusers', 'u.id')
                  ->where('v.fksede', $this->sedeId)
                  ->whereBetween('v.created_at', [$fechaInicioDia, $fechaFinDia]);
        })
        ->orWhereExists(function ($query) use ($fechaInicioDia, $fechaFinDia) {
            $query->select(DB::raw(1))
                  ->from('gastos as g')
                  ->whereColumn('g.fkuser', 'u.id')
                  ->where('g.fksede', $this->sedeId)
                  ->whereDate('g.created_at', $this->fechaInicio);
        })
        ->get();

    // Si no hay usuarios con actividad, obtener al menos los asignados a la sede
        if ($usuarios->isEmpty()) {
            $usuarios = DB::table('users')
                ->where('fksede', $this->sedeId)
                ->get();
        }
        // Datos por usuario
        $datosPorUsuario = [];

        foreach ($usuarios as $usuario) {
            // Pagos generados por el usuario
            $pagosUsuario = DB::table('pago_detalles as pd')
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
                ->where('p.fkuser', $usuario->id)
                ->whereBetween('pd.created_at', [$fechaInicioDia, $fechaFinDia])
                ->groupBy('mp.tipo_pago', 'm.mem_nomb', 'm.mem_durac')
                ->get();

            // Total de pagos por usuario
            $totalPagosUsuario = DB::table('pago_detalles as pd')
                ->join('pagos as p', 'pd.fkpago', '=', 'p.id_pag')
                ->where('p.fksede', $this->sedeId)
                ->where('p.fkuser', $usuario->id)
                ->whereBetween('pd.created_at', [$fechaInicioDia, $fechaFinDia])
                ->sum('pd.monto');

            // Detalle de métodos de pago por usuario
            $metodosPagoUsuario = DB::table('pago_detalles as pd')
                ->join('pagos as p', 'pd.fkpago', '=', 'p.id_pag')
                ->join('metodos_pago as mp', 'pd.fkmetodo', '=', 'mp.id_metod')
                ->select(
                    'mp.tipo_pago as metodo_pago',
                    DB::raw("SUM(pd.monto) as monto_total"),
                    DB::raw("COUNT(*) as cantidad_pagos")
                )
                ->where('p.fksede', $this->sedeId)
                ->where('p.fkuser', $usuario->id)
                ->whereBetween('pd.created_at', [$fechaInicioDia, $fechaFinDia])
                ->groupBy('mp.tipo_pago')
                ->get();

            // Obtener ventas basadas en detalle_venta
            $ventasDetalleUsuario = DB::table('detalle_venta as dv')
                ->join('ventas as v', 'dv.fkventa', '=', 'v.id_venta')
                ->join('metodos_pago as mp', 'v.fkmetodo', '=', 'mp.id_metod')
                ->select(
                    'mp.tipo_pago as metodo_pago',
                    DB::raw('COUNT(DISTINCT dv.fkventa) as total_ventas'),
                    DB::raw('SUM(dv.datelle_sub_total) as monto_total')
                )
                ->where('v.fksede', $this->sedeId)
                ->where('v.fkusers', $usuario->id)
                ->whereBetween('v.created_at', [$fechaInicioDia, $fechaFinDia])
                ->groupBy('mp.tipo_pago')
                ->get();

            // Total de ventas por usuario basado en detalle_venta
            $totalVentasUsuario = DB::table('detalle_venta as dv')
                ->join('ventas as v', 'dv.fkventa', '=', 'v.id_venta')
                ->where('v.fksede', $this->sedeId)
                ->where('v.fkusers', $usuario->id)
                ->whereBetween('v.created_at', [$fechaInicioDia, $fechaFinDia])
                ->sum('dv.datelle_sub_total');

            // Productos vendidos por el usuario (directamente desde detalle_venta)
            $productosVendidosUsuario = DB::table('detalle_venta as dv')
                ->join('productos as p', 'dv.fkproducto', '=', 'p.id_productos')
                ->join('ventas as v', 'dv.fkventa', '=', 'v.id_venta')
                ->select(
                    'p.prod_nombre as producto',
                    DB::raw('SUM(dv.datelle_cantidad) as cantidad_vendida'),
                    DB::raw('SUM(dv.datelle_sub_total) as monto_total_producto')
                )
                ->where('v.fksede', $this->sedeId)
                ->where('v.fkusers', $usuario->id)
                ->whereBetween('v.created_at', [$fechaInicioDia, $fechaFinDia])
                ->groupBy('p.prod_nombre')
                ->get();

            // Gastos realizados por el usuario
            $gastosUsuario = DB::table('gastos as g')
                ->select(
                    'g.gast_categoria as categoria',
                    'g.gast_descripcion as descripcion',
                    DB::raw('SUM(g.gast_monto) as monto_total')
                )
                ->where('g.fksede', $this->sedeId)
                ->where('g.fkuser', $usuario->id)
                ->whereDate('g.created_at', $this->fechaInicio)
                ->groupBy('g.gast_categoria', 'g.gast_descripcion')
                ->get();

            // Total de gastos por usuario
            $totalGastosUsuario = DB::table('gastos')
                ->where('fksede', $this->sedeId)
                ->where('fkuser', $usuario->id)
                ->whereDate('created_at', $this->fechaInicio)
                ->sum('gast_monto');

            // Agregar datos del usuario al array
            $datosPorUsuario[] = [
                'usuario' => $usuario,
                'pagos' => $pagosUsuario,
                'metodosPago' => $metodosPagoUsuario,
                'ventasDetalle' => $ventasDetalleUsuario,
                'productosVendidos' => $productosVendidosUsuario,
                'gastos' => $gastosUsuario,
                'totalPagos' => $totalPagosUsuario,
                'totalVentas' => $totalVentasUsuario,
                'totalGastos' => $totalGastosUsuario,
                'totalIngresos' => $totalPagosUsuario + $totalVentasUsuario,
                'balanceNeto' => ($totalPagosUsuario + $totalVentasUsuario) - $totalGastosUsuario
            ];
        }

        // Totales generales basados en detalle_venta
        $totalPagos = DB::table('pago_detalles as pd')
            ->join('pagos as p', 'pd.fkpago', '=', 'p.id_pag')
            ->where('p.fksede', $this->sedeId)
            ->whereBetween('pd.created_at', [$fechaInicioDia, $fechaFinDia])
            ->sum('pd.monto');

        $totalVentas = DB::table('detalle_venta as dv')
            ->join('ventas as v', 'dv.fkventa', '=', 'v.id_venta')
            ->where('v.fksede', $this->sedeId)
            ->whereBetween('dv.created_at', [$fechaInicioDia, $fechaFinDia])
            ->sum('dv.datelle_sub_total');

        $totalGastos = DB::table('gastos')
            ->where('fksede', $this->sedeId)
            ->whereDate('created_at', $this->fechaInicio)
            ->sum('gast_monto');

        $totalIngresos = $totalPagos + $totalVentas;
        $balanceNeto = $totalIngresos - $totalGastos;

        $fechaReporte = \Carbon\Carbon::parse($this->fechaInicio)->format('d/m/Y');
        $sede = DB::table('sedes')->where('id_sede', $this->sedeId)->value('sede_nombre');

        $data = [
            'sede' => $sede,
            'fechaReporte' => $fechaReporte,
            'datosPorUsuario' => $datosPorUsuario,
            'totalPagos' => $totalPagos,
            'totalVentas' => $totalVentas,
            'totalGastos' => $totalGastos,
            'totalIngresos' => $totalIngresos,
            'balanceNeto' => $balanceNeto
        ];

        $pdf = Pdf::loadView('reporte.ingresos_diarios_pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20)
            ->setOption('margin-left', 20)
            ->setOption('margin-right', 20);

        return $pdf->stream('ingresos_diarios_por_usuario.pdf');
    }
}