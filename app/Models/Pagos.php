<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pag';
    protected $guarded = [];

    public $timestamps = true;


    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'fkalum', 'id_alumno');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'fkuser', 'id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'fksede', 'id_sede');
    }

    public function metodo()
    {
        return $this->belongsTo(Metodo::class, 'fkmetodo', 'id_metod');
    }

    public function membresia()
    {
        return $this->belongsTo(Membresias::class, 'fkmem', 'id_mem');
    }

    public function pagodetalle()
    {
        return $this->hasMany(PagoDetalle::class, 'fkpago');
    }


    public function getPagInicioFormatoAttribute(): string
    {
        return Carbon::parse($this->pag_inicio)->format('d/m/Y');
    }

    public function getPagFinFormatoAttribute(): string
    {
        return Carbon::parse($this->pag_fin)->format('d/m/Y');
    }
    public function getLimiteFormatoAttribute(): string
    {
        return Carbon::parse($this->fecha_limite_pago)->format('d/m/Y');
    }

    public function getFechaPagoFormatoAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado_pago', 'incompleto')
            ->whereDate('fecha_limite_pago', '<', now());
    }


    public function scopePorVencer($query, $dias = 5)
    {
        return $query->where('estado_pago', 'incompleto')
            ->whereDate('fecha_limite_pago', '>=', now())
            ->whereDate('fecha_limite_pago', '<=', now()->addDays($dias));
    }

    public function getPagoVencidoAttribute()
    {
        if ($this->estado_pago === 'incompleto') {
            $fechaLimite = Carbon::parse($this->fecha_limite_pago)->startOfDay();
            return now()->startOfDay()->gt($fechaLimite); // SOLO si es antes de hoy
        }
        return false;
    }

    public function getMensajePagoVencidoAttribute()
    {
        if ($this->pago_vencido) {
            $diasVencidos = Carbon::parse($this->fecha_limite_pago)->diffInDays(now(), false);
            if ($diasVencidos <= 5) {
                return '¡ATENCIÓN! El pago del alumno ' . $this->alumno->alum_nombre . ' está vencido hace ' . $diasVencidos . ' día(s).';
            }
//            return '¡ATENCIÓN! El pago de ' . $this->alumno->alum_nombre . ' está vencido hace ' . $diasVencidos . ' día';

        }

        return null;
    }

    public function getPagoPorVencerAttribute()
    {
        if ($this->estado_pago === 'incompleto') {
            $fechaLimite = Carbon::parse($this->fecha_limite_pago);
            $diasRestantes = now()->diffInDays($fechaLimite, false);

            return $diasRestantes <= 5 && $diasRestantes >= 0;
        }

        return false;
    }

    public function getMensajePagoPorVencerAttribute()
    {
        if ($this->pago_por_vencer) {
            $diasRestantes = now()->diffInDays($this->fecha_limite_pago);
            // Accedemos al nombre del alumno a través de la relación
            return 'Pago incompleto de ' . $this->alumno->alum_nombre . ' por vencer en ' . $diasRestantes . ' días';
        }

        return null;
    }

    public function getAsistenciaCualquierSedeAttribute()
    {
        if ($this->pag_inicio && $this->pag_fin) {
            $inicio = Carbon::parse($this->pag_inicio);
            $fin = Carbon::parse($this->pag_fin);
            $diferenciaDias = $inicio->diffInDays($fin);
            
            return $diferenciaDias > 90;
        }
        
        return false;
    }
    
    public function getDuracionDiasAttribute()
    {
        if ($this->pag_inicio && $this->pag_fin) {
            $inicio = Carbon::parse($this->pag_inicio);
            $fin = Carbon::parse($this->pag_fin);
            return $inicio->diffInDays($fin);
        }
        
        return 0;
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($pago) {
            // Si ya tiene comisión ajustada y no hay fecha límite, no se hace más ajuste
            if ($pago->comision_ajustada !== null && !$pago->fecha_limite_pago) {
                return;
            }

            // Obtener la comisión base de la membresía
            $comisionBase = $pago->membresia->mem_comi;

            // Si no hay fecha límite de pago, no se realiza ajuste
            if (!$pago->fecha_limite_pago) {
                $pago->comision_ajustada = $comisionBase;
                return;
            }

            // Calcular la diferencia de semanas
            $fechaLimite = Carbon::parse($pago->fecha_limite_pago);
            $diasDeDiferencia = Carbon::now()->diffInDays($fechaLimite);
            $semanasDeRetraso = floor($diasDeDiferencia / 7); // Convertir a semanas

            // Calcular la comisión ajustada
            $comisionAjustada = $comisionBase - (5 * $semanasDeRetraso);

            // Evitar que la comisión ajustada sea negativa
            $pago->comision_ajustada = max(0, $comisionAjustada);
        });
    }


}
