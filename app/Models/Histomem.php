<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Histomem extends Model
{
    protected $table = 'hismem';
    protected $primaryKey = 'id_hismem';
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


    
}
