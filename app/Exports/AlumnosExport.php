<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class AlumnosExport implements FromCollection, WithHeadings, WithMapping
{

    protected $sedeId;

    public function __construct($sedeId)
    {
        $this->sedeId = $sedeId;
    }

    public function collection()
    {
        $query = DB::table('alumno as a')
            ->join('sedes as s', 'a.fksede', '=', 's.id_sede')
            ->select(
                's.sede_nombre',
                'a.alum_codigo',
                'a.alum_telefo',
                'a.alum_nombre',
                'a.alum_apellido',
                'a.alum_direccion',
                'a.alum_correro',
                'a.created_at',
                'a.alum_estado'
            )
            ->where('a.fksede', $this->sedeId)
            ->whereNotNull('a.alum_codigo')
            ->orderBy('s.sede_nombre')
            ->orderBy('a.created_at')
            ->get();
        return $query;
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

        return [
            $alumno->sede_nombre,
            $alumno->alum_codigo,
            $telefono,
            $alumno->alum_nombre,
            $alumno->alum_apellido,
            $alumno->alum_direccion,
            $alumno->alum_correro,
            $alumno->created_at,
            $estado
        ];
    }

    public function headings(): array
    {
        return [
            'Sede',
            'Cosido de Alumno',
            'Teléfono',
            'Nombre',
            'Apellido',
            'Dirección',
            'Correo',
            'Fecha de Registro',
            'Estado'
        ];
    }
}
