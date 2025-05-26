<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensaje';
    protected $primaryKey = 'id_mensaje';
    protected $guarded = [];

    public $timestamps = true;


    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'fkalum','id_alumno');
    }


    public function sede()
    {
        return $this->belongsTo(sede::class, 'fksede', 'id_sede');
    }
    public function getFechaFormatoAttribute(): string
    {
        return Carbon::parse($this->mensa_edit)->format('Y-m');
    }

    public function getColorCierreAttribute()
    {
        return match($this->mensa_cerrar) {
            'S' => 'green',
            'N' => 'red',
            'P' => 'blue',
            default => 'blue',
        };
    }
}
