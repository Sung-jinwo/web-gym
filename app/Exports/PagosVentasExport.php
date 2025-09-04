<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
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
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\WithEvents,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
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
            ->leftJoin('alumno as a', 'p.fkalum', '=', 'a.id_alumno') 
            ->leftJoin('users as u', 'p.fkuser', '=', 'u.id') 
            ->join('metodos_pago as mp', 'pd.fkmetodo', '=', 'mp.id_metod')
            ->join('membresias as m', 'pd.fkmemb', '=', 'm.id_mem')
            ->join('sedes as s', 'p.fksede', '=', 's.id_sede')
            ->select(
                'pd.id_pagdeta',
                DB::raw('DATE(pd.created_at) as fecha_pago'), 
                's.sede_nombre',
                'mp.tipo_pago as metodo_pago',
                'p.pag_entre as entrenador',
                'm.mem_nomb as membresia',
                'u.name as usuario',
                'm.mem_durac as duracion',
                'm.mem_limit as fecha',
                'pd.monto',
                'pd.estado',
                'p.comision_ajustada',
                DB::raw("CONCAT(a.alum_nombre, ' ', a.alum_apellido) as alumno") 
            )
            ->where('p.fksede', $this->sedeId);
    
        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('pd.created_at', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        }
    
        return $query->orderBy('pd.created_at', 'asc')->get();
    }
    

    public function map($pago): array
    {
        $alumno = $pago->alumno ?? 'Alumno no especificado';
        $duracionOFecha = $pago->duracion === null 
            ? ($pago->fecha ?? 'Sin fecha') 
            : $pago->duracion . ' días';

        $comision = ($pago->estado === 'completo') 
        ? $pago->comision_ajustada 
        : 0;

        return [
            $pago->id_pagdeta,
            $pago->fecha_pago,
            $pago->sede_nombre,
            $pago->usuario,
            $pago->metodo_pago,
            $pago->entrenador,
            $pago->membresia,
            $alumno,
            $duracionOFecha,
            number_format($pago->monto, 2),
            ucfirst($pago->estado),
            number_format($comision, 2)
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha Pago',
            'Sede',
            'Usuario',
            'Método Pago',
            'Entrenador',
            'Membresía',
            'Alumno',
            'Duración o fecha',
            'Monto (S/.)',
            'Estado',
            'Comisiones generadas (S/.)'
        ];
    }

    public function title(): string
    {
        return 'Detalle Pagos';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                // Estilo para encabezados
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5']
                    ]
                ];
                
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray($headerStyle);
                
                // Estilo para montos (columnas J y L)
                $sheet->getStyle('J2:J' . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('L2:L' . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');
                
                
                // Bordes para toda la tabla
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
        ];
    }
}

class DetalleVentasSheet implements \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithMapping,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\WithEvents,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
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
            ->leftJoin('alumno as a', 'v.fkalum', '=', 'a.id_alumno')
            ->leftJoin('users as u', 'v.fkusers', '=', 'u.id')
            ->join('productos as p', 'dv.fkproducto', '=', 'p.id_productos')
            ->join('metodos_pago as mp', 'v.fkmetodo', '=', 'mp.id_metod')
            ->join('sedes as s', 'v.fksede', '=', 's.id_sede')
            ->select(
                'dv.id_detalle',
                DB::raw('DATE(dv.created_at) as venta_fecha'),
                's.sede_nombre',
                'mp.tipo_pago as metodo_pago',
                'v.venta_entre as entrenador',
                'u.name as usuario',
                'p.prod_nombre as producto',
                DB::raw("CONCAT(a.alum_nombre, ' ', a.alum_apellido) as alumno"),
                'dv.datelle_cantidad as cantidad',
                'dv.datelle_precio_unitario as precio_unitario',
                'dv.datelle_sub_total as subtotal',
                'dv.estado_venta as Estado'
            )
            ->where('v.fksede', $this->sedeId);

        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('dv.created_at', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        }

        return $query->orderBy('dv.created_at', 'asc')->get();
    }

    public function map($venta): array
    {
        $alumno = $venta->alumno ?? 'Alumno no especificado';

        return [
            $venta->id_detalle,
            $venta->venta_fecha,
            $venta->sede_nombre,
            $venta->usuario,
            $venta->metodo_pago,
            $venta->entrenador,
            $venta->producto,
            $alumno,
            $venta->cantidad,
            number_format($venta->precio_unitario, 2),
            number_format($venta->subtotal, 2),
            ucfirst($venta->Estado)
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha Venta',
            'Sede',
            'Usuario',   
            'Método Pago',
            'Entrenador',
            'Producto',
            'Alumno',
            'Cantidad',
            'Precio Unitario (S/.)',
            'Subtotal (S/.)',
            'Estado'
        ];
    }

    public function title(): string
    {
        return 'Detalle Ventas';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                // Estilo para encabezados
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5']
                    ]
                ];
                
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray($headerStyle);
                
                // Estilo para montos (columnas J, K)
                $sheet->getStyle('J2:J' . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('K2:K' . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');
                
                
                
                // Bordes para toda la tabla
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
        ];
    }
}

class GastosSheet implements \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithMapping,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\WithEvents,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
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
        $query = DB::table('gastos as g')
            ->where('g.fksede', $this->sedeId)
            ->leftJoin('users as u', 'g.fkuser', '=', 'u.id')
            ->select(
                'g.id_gasto',
                'g.gast_categoria as categoria',
                'u.name as usuario',
                'g.gast_descripcion as descripcion',
                'g.gast_monto as monto',
                DB::raw('DATE(g.created_at) as fecha'),
            );

        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('g.created_at', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        }

        return $query->orderBy('g.created_at', 'asc')->get();
    }

    public function map($gasto): array
    {
        return [
            $gasto->id_gasto,
            $gasto->fecha,
            $gasto->usuario,
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
            'Usuario',
            'Categoría',
            'Descripción',
            'Monto (S/.)'
        ];
    }

    public function title(): string
    {
        return 'Gastos';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                // Estilo para encabezados
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5']
                    ]
                ];
                
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray($headerStyle);
                
                // Estilo para montos (columna F)
                $sheet->getStyle('F2:F' . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');
                
                
                // Bordes para toda la tabla
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
        ];
    }
}

class ResumenFinancieroSheet implements \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\WithEvents,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
{
    protected $sedeId;
    protected $fechaInicio;
    protected $fechaFin;
    protected $balanceNeto;

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
                $query->whereBetween('dv.created_at', [
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
        $this->balanceNeto = ($totalPagos + $totalVentas) - $totalGastos;

        return collect([
            ['Concepto' => 'Total Ingresos por Pagos', 'Monto' => number_format($totalPagos, 2)],
            ['Concepto' => 'Total Ingresos por Ventas', 'Monto' => number_format($totalVentas, 2)],
            ['Concepto' => 'Total Ingresos', 'Monto' => number_format($totalPagos + $totalVentas, 2)],
            ['Concepto' => 'Total Gastos', 'Monto' => number_format($totalGastos, 2)],
            ['Concepto' => 'Balance Neto', 'Monto' => number_format($this->balanceNeto, 2)]
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                // Estilo para encabezados
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5']
                    ]
                ];
                
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray($headerStyle);
                
                // Estilo para montos (columna B)
                $sheet->getStyle('B2:B' . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');
                
                // Estilo para fila de balance neto
                $sheet->getStyle('A' . $highestRow . ':' . $highestColumn . $highestRow)
                    ->getFont()
                    ->setBold(true)
                    ->setColor(new Color('FFFFFF'));
                
                $sheet->getStyle('A' . $highestRow . ':' . $highestColumn . $highestRow)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                     ->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color($this->balanceNeto >= 0 ? '198754' : 'DC3545'));
                
                // Bordes para toda la tabla
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
        ];
    }
}