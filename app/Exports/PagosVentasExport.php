<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagosVentasExport implements WithMultipleSheets
{
    protected $sedeId;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($sedeId, $fechaInicio = null, $fechaFin = null)
    {
        $this->sedeId = $sedeId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function sheets(): array
    {
        return [
            'Detalle Pagos' => new DetallePagosSheet($this->sedeId, $this->fechaInicio, $this->fechaFin),
            'Detalle Ventas' => new DetalleVentasSheet($this->sedeId, $this->fechaInicio, $this->fechaFin),
            'Gastos' => new GastosSheet($this->sedeId, $this->fechaInicio, $this->fechaFin),
            'Resumen Financiero' => new ResumenFinancieroSheet($this->sedeId, $this->fechaInicio, $this->fechaFin)
        ];
    }
}

class DetallePagosSheet implements \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithMapping,
    \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $sedeId;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($sedeId, $fechaInicio, $fechaFin)
    {
        $this->sedeId = $sedeId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function collection()
    {
        $query = DB::table('pago_detalles as pd')
            ->join('pagos as p', 'pd.fkpago', '=', 'p.id_pag')
            ->join('metodos_pago as mp', 'pd.fkmetodo', '=', 'mp.id_metod')
            ->join('membresias as m', 'pd.fkmemb', '=', 'm.id_mem')
            ->join('sedes as s', 'p.fksede', '=', 's.id_sede')
            ->select(
                'pd.id_pagdeta',
                'pd.created_at as fecha_pago',
                'mp.tipo_pago as metodo_pago',
                'm.mem_nomb as membresia',
                'm.mem_durac as duracion',
                'pd.monto',
                'pd.estado',
                's.sede_nombre'
            )
            ->where('p.fksede', $this->sedeId);

        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('pd.created_at', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        }

        return $query->orderBy('pd.created_at', 'desc')->get();
    }

    public function map($pago): array
    {
        return [
            $pago->id_pagdeta,
            $pago->fecha_pago,
            $pago->sede_nombre,
            $pago->metodo_pago,
            $pago->membresia,
            $pago->duracion . ' días',
            number_format($pago->monto, 2),
            ucfirst($pago->estado)
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha Pago',
            'Sede',
            'Método Pago',
            'Membresía',
            'Duración',
            'Monto (S/.)',
            'Estado'
        ];
    }

    public function title(): string
    {
        return 'Detalle Pagos';
    }
}

class DetalleVentasSheet implements \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithMapping,
    \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $sedeId;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($sedeId, $fechaInicio, $fechaFin)
    {
        $this->sedeId = $sedeId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function collection()
    {
        $query = DB::table('detalle_venta as dv')
            ->join('ventas as v', 'dv.fkventa', '=', 'v.id_venta')
            ->join('productos as p', 'dv.fkproducto', '=', 'p.id_productos')
            ->join('metodos_pago as mp', 'v.fkmetodo', '=', 'mp.id_metod')
            ->join('sedes as s', 'v.fksede', '=', 's.id_sede')
            ->select(
                'dv.id_detalle',
                'v.venta_fecha',
                's.sede_nombre',
                'p.prod_nombre as producto',
                'dv.datelle_cantidad as cantidad',
                'dv.datelle_precio_unitario as precio_unitario',
                'dv.datelle_sub_total as subtotal',
                'mp.tipo_pago as metodo_pago'
            )
            ->where('v.fksede', $this->sedeId);

        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('v.venta_fecha', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        }

        return $query->orderBy('v.venta_fecha', 'desc')->get();
    }

    public function map($venta): array
    {
        return [
            $venta->id_detalle,
            $venta->venta_fecha,
            $venta->sede_nombre,
            $venta->producto,
            $venta->cantidad,
            number_format($venta->precio_unitario, 2),
            number_format($venta->subtotal, 2),
            $venta->metodo_pago
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha Venta',
            'Sede',
            'Producto',
            'Cantidad',
            'Precio Unitario (S/.)',
            'Subtotal (S/.)',
            'Método Pago'
        ];
    }

    public function title(): string
    {
        return 'Detalle Ventas';
    }
}

class GastosSheet implements \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithMapping,
    \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $sedeId;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($sedeId, $fechaInicio, $fechaFin)
    {
        $this->sedeId = $sedeId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function collection()
    {
        $query = DB::table('gastos')
            ->where('fksede', $this->sedeId)
            ->select(
                'id_gasto',
                'gast_categoria as categoria',
                'gast_descripcion as descripcion',
                'gast_monto as monto',
                'created_at as fecha'
            );

        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function map($gasto): array
    {
        return [
            $gasto->id_gasto,
            $gasto->fecha,
            $gasto->categoria,
            $gasto->descripcion,
            number_format($gasto->monto, 2)
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Categoría',
            'Descripción',
            'Monto (S/.)'
        ];
    }

    public function title(): string
    {
        return 'Gastos';
    }
}

class ResumenFinancieroSheet implements \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $sedeId;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($sedeId, $fechaInicio, $fechaFin)
    {
        $this->sedeId = $sedeId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function collection()
    {
        // Total Pagos
        $totalPagos = DB::table('pago_detalles as pd')
            ->join('pagos as p', 'pd.fkpago', '=', 'p.id_pag')
            ->where('p.fksede', $this->sedeId)
            ->when($this->fechaInicio && $this->fechaFin, function($query) {
                $query->whereBetween('pd.created_at', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            })
            ->sum('pd.monto');

        // Total Ventas
        $totalVentas = DB::table('detalle_venta as dv')
            ->join('ventas as v', 'dv.fkventa', '=', 'v.id_venta')
            ->where('v.fksede', $this->sedeId)
            ->when($this->fechaInicio && $this->fechaFin, function($query) {
                $query->whereBetween('v.venta_fecha', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            })
            ->sum('dv.datelle_sub_total');

        // Total Gastos
        $totalGastos = DB::table('gastos')
            ->where('fksede', $this->sedeId)
            ->when($this->fechaInicio && $this->fechaFin, function($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            })
            ->sum('gast_monto');

        // Balance Neto
        $balanceNeto = ($totalPagos + $totalVentas) - $totalGastos;

        return collect([
            ['Concepto' => 'Total Ingresos por Pagos', 'Monto' => number_format($totalPagos, 2)],
            ['Concepto' => 'Total Ingresos por Ventas', 'Monto' => number_format($totalVentas, 2)],
            ['Concepto' => 'Total Ingresos', 'Monto' => number_format($totalPagos + $totalVentas, 2)],
            ['Concepto' => 'Total Gastos', 'Monto' => number_format($totalGastos, 2)],
            ['Concepto' => 'Balance Neto', 'Monto' => number_format($balanceNeto, 2)]
        ]);
    }

    public function headings(): array
    {
        return ['Concepto', 'Monto (S/.)'];
    }

    public function title(): string
    {
        return 'Resumen Financiero';
    }
}
