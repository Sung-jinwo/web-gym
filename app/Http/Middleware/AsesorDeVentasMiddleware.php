<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AsesorDeVentasMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
    
        if (!$user) {
            return redirect('/login')->with('error', 'Debes iniciar sesión');
        }

        $rolesRestringidos = [User::ROL_VENTAS, User::ROL_ASISTENCIA];

        if (in_array($user->rol, $rolesRestringidos)) {
            return redirect('/')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);

    }
}
