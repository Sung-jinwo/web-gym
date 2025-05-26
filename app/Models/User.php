<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const ROL_ADMIN = 0;
    const ROL_EMPLEADO = 1;
    const ROL_ASISTENCIA = 2;

    public function is($rol)
    {
        return $this->rol == $rol;
    }

    public function sede()
    {
        return $this->belongsTo(sede::class, 'fksede', 'id_sede');
    }

    public static function withRolesAdminAndEmpleado()
    {
        return self::whereIn('rol', [self::ROL_ADMIN, self::ROL_EMPLEADO])->get();
    }

     public function getNombreRolAttribute(): ?string
     {
         $arr = [
             self::ROL_ADMIN => 'Administrador',
             self::ROL_EMPLEADO => 'Empleado',
             self::ROL_ASISTENCIA => 'Asistencia',
         ];

         return $arr[$this->rol] ?? null;
     }

}
