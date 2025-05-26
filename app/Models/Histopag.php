<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Histopag extends Model
{
    protected $table = 'hispag';
    protected $primaryKey = 'id_hispag';
    protected $guarded = [];

    public $timestamps = true;


    public function membresia()
    {
        return $this->belongsTo(Membresias::class, 'fkmem');
    }

    // Relación con la tabla users
    public function user()
    {
        return $this->belongsTo(User::class, 'fkuser');
    }

    public function Pagos()
    {
        return $this->belongsTo(Pagos::class, 'fkpago');
    }

    public function Alumno()
    {
        return $this->belongsTo(Alumno::class, 'fkalum');
    }



}
