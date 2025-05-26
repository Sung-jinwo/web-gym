<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoDetalle extends Model
{
    protected $table = 'pago_detalles';
    protected $primaryKey = 'id_pagdeta';
    protected $guarded = [];

    public $timestamps = true;


    public function pago()
    {
        return $this->belongsTo(Pagos::class, 'fkpago', 'id_pag');
    }

    public function metodo()
    {
        return $this->belongsTo(Metodo::class, 'fkmetodo', 'id_metod');
    }


    public function membresia()
    {
        return $this->belongsTo(Membresias::class, 'fkmemb', 'id_mem');
    }

    public function getPagoFormatoAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }
    public function getPagoFormatoHoraAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('H:i:s');
    }


}
