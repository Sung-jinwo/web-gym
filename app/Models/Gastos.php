<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
    protected $table = 'gastos';
    protected $primaryKey = 'id_gasto';
    protected $guarded = [];
    public $timestamps = true;


    public function sede()
    {
        return $this->belongsTo(sede::class, 'fksede', 'id_sede');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'fkuser', 'id');
    }

}
