<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventarioExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Producto::with(['categoria', 'sede'])
            ->select(
                'id_productos',
                'prod_nombre',
                'prod_cantidad',
                'prod_precio',
                'fkcategoria',
                'fksede',
                'estado'
            )
            ->orderBy('prod_nombre')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre del Producto',
            'Cantidad en Stock',
            'Precio Unitario',
            'Valor Total Los Productos',
            'Categoría',
            'Sede',
            'Estado'
        ];
    }

    public function map($producto): array
    {
        return [
            $producto->id_productos,
            $producto->prod_nombre,
            $producto->prod_cantidad,
            number_format($producto->prod_precio, 2),
            number_format($producto->prod_cantidad * $producto->prod_precio, 2),
            $producto->categoria->nombre ?? 'Sin categoría',
            $producto->sede->sede_direccion ?? 'Sin sede asignada',
            ucfirst($producto->estado)
        ];
    }
}