<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctegoria_m extends Model
{
    protected $table = 'categoria_m';
    protected $primaryKey = 'id_categoria_m';
    protected $guarded = [];

    public $timestamps = true;

    public function membresias()
    {
        return $this->hasMany(Membresias::class, 'fkcategoria', 'id_categoria_m');
    }

}
