<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_venta';
    protected $primaryKey = 'id_detalle';
    protected $guarded = [];

    public $timestamps = true;

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'fkventa', 'id_venta');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'fkproducto', 'id_productos');
    }

    public function metodo()
    {
        return $this->belongsTo(Metodo::class, 'fkmetodo', 'id_metod');
    }


    public function getDetalleVentaFormatoAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }
    public function getDetalleVentaFormatoHoraAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('H:i:s');
    }


}

