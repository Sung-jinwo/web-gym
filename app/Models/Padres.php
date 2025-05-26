<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Padres extends Model
{
    protected $table = 'padres';
    protected $primaryKey = 'id_padre';
    protected $guarded = [];


    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'fkalumno', 'id_alumno');
    }

}
