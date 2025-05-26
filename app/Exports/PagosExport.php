<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $sedeId;

    public function __construct($fechaInicio = null, $fechaFin = null, $sedeId)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->sedeId = $sedeId;
    }

    public function collection()
    {
        $query = DB::table('pagos as p')
            ->join('metodos_pago as mp', 'p.fkmetodo', '=', 'mp.id_metod')
            ->join('sedes as s', 'p.fksede', '=', 's.id_sede')
            ->join('alumno as a', 'p.fkalum', '=', 'a.id_alumno')
            ->join('membresias as m', 'p.fkmem', '=', 'm.id_mem')
            ->select(
                's.sede_nombre',
                'p.created_at as fecha_pago',
                'p.pag_fin',
                'p.pago',
                'mp.tipo_pago',
                'p.pag_entre',
                'a.alum_nombre',
                'a.alum_apellido',
                'a.alum_telefo',
                'm.mem_nomb',
                'p.tipo_membresia',
                'p.estado_pago',
                'p.fecha_limite_pago',
                'p.saldo_pendiente',
                'p.monto_pagado'
            )
            ->where('p.fksede', $this->sedeId);

        // Aplicar filtro de fechas si están presentes
        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('p.created_at', [$this->fechaInicio, $this->fechaFin]);
        }

        return $query->orderBy('s.sede_nombre')
            ->orderBy('p.created_at')
            ->get();
    }

    public function map($pago): array
    {
        // Formatear teléfono
        $telefono = $this->formatearTelefono($pago->alum_telefo);

        // Determinar estado de membresía
        $estadoMembresia = $this->determinarEstadoMembresia($pago->pag_fin);

        // Formatear montos
        $montoPago = number_format($pago->pago, 2);
        $saldoPendiente = number_format($pago->saldo_pendiente, 2);
        $montoPagado = number_format($pago->monto_pagado, 2);

        // Formatear estado de pago
        $estadoPago = $pago->estado_pago == 'completo' ? 'Completo' : 'Incompleto';

        return [
            $pago->sede_nombre,
            $pago->fecha_pago,
            $pago->pag_fin,
            $montoPago,
            $pago->tipo_pago,
            $pago->pag_entre,
            $pago->alum_nombre,
            $pago->alum_apellido,
            $telefono,
            $pago->mem_nomb,
            $pago->tipo_membresia == 'principal' ? 'Principal' : 'Adicional',
            $estadoPago,
            $pago->fecha_limite_pago,
            $saldoPendiente,
            $montoPagado,
            $estadoMembresia
        ];
    }

    public function headings(): array
    {
        return [
            'Sede',
            'Fecha de Pago',
            'Fecha Fin Membresía',
            'Monto Pago (S/.)',
            'Tipo de Pago',
            'Pago Trainer',
            'Nombre Alumno',
            'Apellido Alumno',
            'Teléfono',
            'Membresía',
            'Tipo de Membresía',
            'Estado de Pago',
            'Fecha Límite de Pago',
            'Saldo Pendiente (S/.)',
            'Monto Pagado (S/.)',
            'Estado Membresía'
        ];
    }

    protected function formatearTelefono($telefono)
    {
        if ($telefono && strlen($telefono) == 9 && substr($telefono, 0, 2) != '51') {
            return '51' . $telefono;
        }
        return $telefono;
    }

    protected function determinarEstadoMembresia($pagFin)
    {
        if (!$pagFin) return "Sin membresía";

        $hoy = Carbon::now();
        $fechaFin = Carbon::parse($pagFin);

        if ($fechaFin->lessThan($hoy)) {
            return "Vencido";
        } elseif ($fechaFin->diffInDays($hoy) <= 5) {
            return "Por caducar / Renovar";
        } else {
            return "Vigente";
        }
    }
}
