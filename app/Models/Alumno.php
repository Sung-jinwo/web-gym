<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumno';
    protected $primaryKey = 'id_alumno';
    protected $guarded = [];
    public $timestamps = true;


    public function pagos()
    {
        return $this->hasMany(Pagos::class, 'fkalum', 'id_alumno');
    }

    public function padres()
    {
        return $this->hasMany(Padres::class, 'fkalumno', 'id_alumno');
    }

    public function sede()
    {
        return $this->belongsTo(sede::class, 'fksede', 'id_sede');
    }

    public function mensaje()
    {
        return $this->hasOne(Mensaje::class, 'fkalum', 'id_alumno');
    }

    public function asistencia()
    {
        return $this->hasMany(Asistensias::class, 'fkalum', 'id_alumno');
    }
    public function getMembresiaVigenteAttribute()
    {
        $fechaActual = now()->format('Y-m-d');

        return $this->pagos()
            ->where('tipo_membresia', 'principal')
            ->where('pag_fin', '>=', $fechaActual)
            ->first();
    }


    public function getAlumEdaAttribute(): int // alum_edad = alumEda
    {
        $now = now();

        $fechaNac = Carbon::parse($this->fecha_nac);

        return $fechaNac->diffInYears($now);
    }


    public function getAlumnoFormatoAttribute(): string
    {
        return Carbon::parse($this->fecha_nac)->format('d/m/Y');
    }

    public function getRegistroFormatoAttribute(): string
    {
        return 'dia'.Carbon::parse($this->created_at)->format('d') . ' de ' . Carbon::parse($this->created_at)->locale('es')->monthName . ' del ' . Carbon::parse($this->created_at)->format('Y');
    }

    public function getAlumnoCreatedAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }


    public function getEstadoMembresiaAttribute(): array
    {
        $pago = $this->membresiaVigente;

        if (!$pago || !$pago->pag_fin) {
            return [
                'estado' => 'Sin membresía',
                'clase'  => 'status-inactive',
            ];
        }

        $diferencia = now()->diffInDays(Carbon::parse($pago->pag_fin), false);

        if ($diferencia < 0) {
            return [
                'estado' => 'Vencido',
                'clase'  => 'status-expired',
            ];
        }

        if ($diferencia <= 5) {
            return [
                'estado' => 'Por caducar / Renovar',
                'clase'  => 'status-expiring',
            ];
        }

        return [
            'estado' => 'Vigente',
            'clase'  => 'status-active',
        ];
    }

    public function getClaseEstadoAttribute(): string
    {
        return $this->estado_membresia['clase'];
    }

    public function getTextoMembresiaAttribute(): string
    {
        return $this->estado_membresia['estado'];
    }

    public function getEstadoPagoAttribute()
    {
        $pagoPrincipal = $this->membresiaVigente;

        return $pagoPrincipal ? $pagoPrincipal->estado_pago : 'Sin pago';

    }
    public function getClasePagoAttribute(): string
    {
        $arr = [
            'completo' => 'status-active',
            'incompleto' => 'status-expiring',
        ];

        return $arr[$this->estadoPago] ?? 'status-inactive';
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->alum_nombre.' '.$this->alum_apellido ;
    }
}
