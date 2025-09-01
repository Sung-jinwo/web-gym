<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlumnosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $sedeId;
    protected $filtroEstado;
    protected $filtroMembresia;

    public function __construct($sedeId, $filtroEstado = null, $filtroMembresia = null)
    {
        $this->sedeId = $sedeId;
        $this->filtroEstado = $filtroEstado;
        $this->filtroMembresia = $filtroMembresia;
    }

    public function collection()
    {
        // Subconsulta para obtener el último pago principal de cada alumno
        $ultimosPagos = DB::table('pagos as p1')
            ->select('p1.fkalum', DB::raw('MAX(p1.id_pag) as ultimo_pago_id'))
            ->where('p1.tipo_membresia', 'principal')
            ->groupBy('p1.fkalum');
            
        $query = DB::table('alumno as a')
            ->join('sedes as s', 'a.fksede', '=', 's.id_sede')
            ->leftJoinSub($ultimosPagos, 'ultimos_pagos', function($join) {
                $join->on('a.id_alumno', '=', 'ultimos_pagos.fkalum');
            })
            ->leftJoin('pagos as p', 'ultimos_pagos.ultimo_pago_id', '=', 'p.id_pag')
            ->leftJoin('membresias as m', 'p.fkmem', '=', 'm.id_mem')
            ->select(
                's.sede_nombre',
                'a.alum_codigo',
                'a.alum_telefo',
                'a.alum_nombre',
                'a.alum_apellido',
                'a.alum_direccion',
                'a.alum_correro',
                'a.created_at',
                'a.alum_estado',
                'p.pag_fin',
                'p.estado_pago',
                'm.mem_nomb as membresia_nombre',
                DB::raw('CASE 
                    WHEN p.id_pag IS NULL THEN "Sin membresía"
                    WHEN p.pag_fin IS NULL THEN "Sin fecha fin"
                    WHEN p.pag_fin < CURDATE() THEN "Vencido"
                    WHEN p.pag_fin <= DATE_ADD(CURDATE(), INTERVAL 5 DAY) THEN "Por caducar / Renovar"
                    ELSE "Vigente"
                END as estado_membresia')
            )
            ->where('a.fksede', $this->sedeId)
            ->whereNotNull('a.alum_codigo');

        // Aplicar filtros adicionales si existen
        if ($this->filtroEstado && $this->filtroEstado !== 'todos') {
            $query->where('a.alum_estado', $this->filtroEstado);
        }

        if ($this->filtroMembresia) {
            switch($this->filtroMembresia) {
                case 'vigente':
                    $query->where('p.pag_fin', '>=', Carbon::now()->addDays(6)->format('Y-m-d'));
                    break;
                case 'por_caducar':
                    $query->whereBetween('p.pag_fin', [
                        Carbon::now()->format('Y-m-d'),
                        Carbon::now()->addDays(5)->format('Y-m-d')
                    ]);
                    break;
                case 'vencido':
                    $query->where('p.pag_fin', '<', Carbon::now()->format('Y-m-d'));
                    break;
                case 'sin_membresia':
                    $query->whereNull('p.id_pag');
                    break;
            }
        }

        return $query->orderBy('s.sede_nombre')
                    ->orderBy('a.created_at')
                    ->get();
    }

    public function map($alumno): array
    {
        // Formatear teléfono (agregar 51 si es necesario)
        $telefono = $alumno->alum_telefo;
        if ($telefono && strlen($telefono) == 9 && substr($telefono, 0, 2) != '51') {
            $telefono = '51' . $telefono;
        }

        // Determinar estado legible
        $estado = $alumno->alum_estado == 'A' ? 'Activo' : 'Inactivo';
        
        // Formatear fecha de registro
        $fechaRegistro = $alumno->created_at 
            ? Carbon::parse($alumno->created_at)->format('d/m/Y') 
            : 'Sin fecha';
            
        // Formatear fecha de fin de membresía
        $fechaFinMembresia = $alumno->pag_fin 
            ? Carbon::parse($alumno->pag_fin)->format('d/m/Y') 
            : 'Sin fecha';

        return [
            $alumno->sede_nombre,
            $alumno->alum_codigo,
            $telefono,
            $alumno->alum_nombre,
            $alumno->alum_apellido,
            $alumno->alum_direccion,
            $alumno->alum_correro,
            $fechaRegistro,
            $estado,
            $alumno->membresia_nombre ?? 'Sin membresía',
            $fechaFinMembresia,
            $alumno->estado_pago ?? 'Sin pago',
            $alumno->estado_membresia
        ];
    }

    public function headings(): array
    {
        return [
            'Sede',
            'Código de Alumno',
            'Teléfono',
            'Nombre',
            'Apellido',
            'Dirección',
            'Correo',
            'Fecha de Registro',
            'Estado Alumno',
            'Membresía',
            'Fecha Fin Membresía',
            'Estado Pago',
            'Estado Membresía'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila de encabezados
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']]
            ],
            
            // Estilo para las celdas según el estado de membresía
            'M2:M' . ($sheet->getHighestRow()) => [
                'font' => [
                    'bold' => true
                ]
            ]
        ];
    }
}