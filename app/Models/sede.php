<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sede extends Model
{
    protected $table = 'sedes';
    protected $primaryKey = 'id_sede';
    protected $guarded = [];

    public $timestamps = true;
}
