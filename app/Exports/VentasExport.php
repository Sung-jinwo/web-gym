<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class VentasExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Venta::join('users', 'ventas.fkusers', '=', 'users.id')
            ->join('sedes', 'ventas.fksede', '=', 'sedes.id_sede')
            ->join('detalle_venta', 'ventas.id_venta', '=', 'detalle_venta.fkventa')
            ->join('productos', 'detalle_venta.fkproducto', '=', 'productos.id_productos')
            ->join('metodos_pago as mp', 'ventas.fkmetodo', '=', 'mp.id_metod')
            ->where('ventas.fksede', $this->sedeId)
            ->select(
                'ventas.id_venta',
                'ventas.venta_entre as vendedor',
                'sedes.sede_direccion',
                'ventas.venta_fecha',
                'productos.prod_nombre as producto',
                'mp.tipo_pago as Metodo_de_Pago',
                'ventas.venta_total'
            );

        // Aplicar filtro de fechas solo si se proporcionan
        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('ventas.venta_fecha', [$this->fechaInicio, $this->fechaFin]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Venta',
            'Vendedor',
            'Sede',
            'Fecha de Venta',
            'Producto',
            'Metodo_de_Pago',
            'Total de Venta'
        ];
    }

    public function map($venta): array
    {
        $vendedor = $venta->vendedor ? $venta->vendedor : 'Asesor';
        return [
            $venta->id_venta,
            $vendedor,
            $venta->sede_nombre,
            $venta->venta_fecha,
            $venta->producto,
            $venta->Metodo_de_Pago,
            $venta->venta_total,
        ];
    }
}
