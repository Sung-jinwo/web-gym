<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Histoprod extends Model
{
    protected $table = 'hisprod';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public $timestamps = true;

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'fkproducto');
    }

    // Relación con la tabla users
    public function user()
    {
        return $this->belongsTo(User::class, 'fkuser');
    }

    
}
