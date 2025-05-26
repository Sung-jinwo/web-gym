<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsistenciasExport implements FromCollection, WithHeadings, WithMapping
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
        $query = DB::table('visitas as v')
            ->join('sedes as s', 'v.fksede', '=', 's.id_sede')
            ->join('alumno as a', 'v.fkalum', '=', 'a.id_alumno')
            ->select(
                's.sede_nombre',
                'v.visi_fecha',
                'a.alum_nombre',
                'a.alum_apellido',
                'a.alum_telefo',
                'a.alum_estado'
            )
            ->where('v.fksede', $this->sedeId);

        // Aplicar filtro de fechas si están presentes
        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('v.visi_fecha', [$this->fechaInicio, $this->fechaFin]);
        }

        return $query->orderBy('s.sede_direccion')
            ->orderBy('v.visi_fecha')
            ->get();
    }

    public function map($asistencia): array
    {
        // Formatear teléfono
        $telefono = $this->formatearTelefono($asistencia->alum_telefo);

        // Determinar estado legible
        $estado = $asistencia->alum_estado == 'A' ? 'Activo' : 'Inactivo';

        // Formatear fecha de visita
        $fechaVisita = Carbon::parse($asistencia->visi_fecha)->format('d/m/Y H:i');

        return [
            $asistencia->sede_nombre,
            $fechaVisita,
            $asistencia->alum_nombre,
            $asistencia->alum_apellido,
            $telefono,
            $estado
        ];
    }

    public function headings(): array
    {
        return [
            'Sede',
            'Fecha y Hora de Visita',
            'Nombre Alumno',
            'Apellido Alumno',
            'Teléfono',
            'Estado Alumno'
        ];
    }

    protected function formatearTelefono($telefono)
    {
        if ($telefono && strlen($telefono) == 9 && substr($telefono, 0, 2) != '51') {
            return '51' . $telefono;
        }
        return $telefono;
    }
}
