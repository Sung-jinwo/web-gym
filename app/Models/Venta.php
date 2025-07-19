<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    protected $guarded = [];

    public $timestamps = true;


    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'fkalum', 'id_alumno');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'fkusers', 'id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'fksede', 'id_sede');
    }

    public function metodo()
    {
        return $this->belongsTo(Metodo::class, 'fkmetodo', 'id_metod');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'fkventa');
    }

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'fkproducto', 'id_productos');
    }


}
