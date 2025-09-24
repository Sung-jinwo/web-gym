<?php

namespace App\Http\Controllers;

use App\Models\Histomem;
use App\Models\Histopag;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sede;
use App\Models\Alumno;
use App\Models\Metodo;
use App\Models\Pagos;
use App\Models\Membresias;
use App\Models\PagoDetalle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreatePagosRequests;



class PagosContoller extends Controller
{
        public function pagosCompletos(Request $request)
        {
            $sedes = Sede::all();
            $membresias = Membresias::orderBy('mem_nomb', 'asc')->get();

            $user = Auth::user();
            $alumnoTexto = $request->get('alumnoTexto');
            $idSede = $request->input('id_sede');
            $id_membresias = $request->input('id_membresias');
            $fechaFiltro = $request->input('fecha_filtro', Carbon::now()->format('Y-m'));


            $query = Pagos::with(['alumno', 'sede', 'membresia']);

            // Aplicar filtros según el rol del usuario
            if (($user->is(User::ROL_ADMIN) ) && $idSede) {
                $query->where('fksede', $idSede);
            } elseif ($user->is(User::ROL_EMPLEADO)|| $user->is(User::ROL_VENTAS)) {
                $query->where('fkuser', $user->id);
            }

            if ($id_membresias) {
                $query->where('fkmem', $request->id_membresias);
            }


            if ($fechaFiltro){
                $fecha = Carbon::parse($fechaFiltro);
                $query->whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month);
            }

            if ($alumnoTexto) {
                $query->whereHas('alumno',function ($q) use ($alumnoTexto) {
                    $q->where('alum_codigo', $alumnoTexto)
                        ->orWhere('alum_nombre', 'LIKE', '%' . $alumnoTexto . '%')
                        ->orWhere('alum_telefo', 'LIKE', '%' . $alumnoTexto . '%')
                        ->orWhere('alum_apellido', 'LIKE', '%' . $alumnoTexto . '%')
                        ->orWhere(Alumno::raw("CONCAT(alum_nombre, ' ', alum_apellido)"), 'LIKE', '%' . $alumnoTexto . '%');
                });
            }

            $pagos = $query ->where('estado_pago', 'completo')
                ->orderBy('updated_at', 'desc')
                ->paginate(12)->appends(request()->query());

            return view('pagos.pagos_completos',
                    compact('pagos','sedes', 'membresias','fechaFiltro'));

        }

        public function pagosIncompletos(Request $request)
        {

            $sedes = Sede::all();
            $membresias = Membresias::orderBy('mem_nomb', 'asc')->get();

            $user = Auth::user();
            $alumnoTexto = $request->get('alumnoTexto');
            $sedeFiltro = $request->input('id_sede');
            $id_membresias = $request->input('id_membresias');
            $fechaFiltro = $request->input('fecha_filtro', Carbon::now()->format('Y-m'));



            $query = Pagos::with(['alumno', 'user', 'sede', 'metodo', 'membresia'])
                ->where('estado_pago', 'incompleto');




            if (($user->is(User::ROL_ADMIN)) && $sedeFiltro) {
                $query->where('fksede', $sedeFiltro);
            } elseif ($user->is(User::ROL_EMPLEADO)|| $user->is(User::ROL_VENTAS)) {
                $query->where('fkuser', $user->id);
            }

            if ($id_membresias) {
                $query->where('fkmem', $request->id_membresias);
            }

            if ($fechaFiltro){
                $fecha = Carbon::parse($fechaFiltro);
                $query->whereYear('fecha_limite_pago', $fecha->year)
                    ->whereMonth('fecha_limite_pago', $fecha->month);
            }


            if ($alumnoTexto) {
                $query->whereHas('alumno',function ($q) use ($alumnoTexto) {
                    $q->where('alum_telefo', $alumnoTexto)
                        ->orWhere('alum_nombre', 'LIKE', '%' . $alumnoTexto . '%')
                        ->orWhere('alum_apellido', 'LIKE', '%' . $alumnoTexto . '%')
                        ->orWhere(Alumno::raw("CONCAT(alum_nombre, ' ', alum_apellido)"), 'LIKE', '%' . $alumnoTexto . '%');
                });
            }


            $pagos = $query ->where('estado_pago', 'incompleto')
                ->orderBy('updated_at', 'desc')
                ->paginate(12)->appends(request()->query());


            $pagosCollection = collect($pagos->items());

            // Filtrar pagos por vencer
            $pagosPorVencer = $pagosCollection->filter(fn($pago) => $pago->pago_por_vencer);
            if ($pagosPorVencer->isNotEmpty()) {
                $mensajesWarning = $pagosPorVencer->map(fn($pago) => $pago->mensaje_pago_por_vencer);
                session()->flash('warning', $mensajesWarning->implode('<br><br>'));
            }
            
            // PAGOS VENCIDOS
            $pagosVencidos = $pagosCollection->filter(fn($pago) => $pago->pago_vencido);
            if ($pagosVencidos->isNotEmpty()) {
                $mensajesError = $pagosVencidos->map(fn($pago) => $pago->mensaje_pago_vencido);
                session()->flash('error', $mensajesError->implode('<br>'));
            }

            return view('pagos.pagos_incompletos',
                 compact('sedes', 'membresias', 'fechaFiltro','pagos'));


        }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $alumnoId = $request->input('alumno_id');
        $users = User::withRolesAdminAndEmpleado();
        $alumno = Alumno::find($alumnoId);
        $fksedeAlumno = $alumno ? $alumno->fksede : null;
        $codigoAlumno = $alumno ? $alumno->alum_codigo : null;
        $membresiasActivas = Membresias::with(['categoria_m'])
            ->where('estado', 'A')
            ->orderBy('mem_nomb', 'asc')
            ->get();
        return view('pagos.pagcreate', [
            'pago' => new Pagos(),
            'sede' => Sede::all(),
            'users' => $users,
            'alumno' => Alumno::all(),
            'membresia' => $membresiasActivas,
            'metodo' => Metodo::all(),
            'montoActual' => 0,
            'codigoAlumno' => $codigoAlumno,
            'sedeAlumno' => $fksedeAlumno,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePagosRequests $request)
    {
        // dd($request->all());
        $validatedData = $request->validated();

        $membresia = Membresias::with('categoria_m')->find($validatedData['fkmem']);
        if (!$membresia) {
            return back()->with('error', 'La membresía seleccionada no existe.');
        }

        // $membresia->tipo;
        $tipoMembresia = $membresia->tipo;

        $estadoPago = $validatedData['estado_pago'];
        //limpiar si no es completo
        $fechaLimitePago = $estadoPago === 'incompleto' ? $request->input('fecha_limite_pago') : null;
        $saldoPendiente = $estadoPago === 'incompleto' ? $validatedData['saldo_pendiente'] : 0;
        // $montoPagado = $estadoPago === 'incompleto' ? $validatedData['monto_pagado'] : 0;
        // $saldoPendiente = $validatedData['saldo_pendiente'];


        $fechaLimitePago = null;
        if ($estadoPago === 'incompleto' && $request->has('fecha_limite_pago')) {
            $fechaLimitePago = $request->input('fecha_limite_pago');
        }
        // $pagoTotal = $validatedData['pago'];
        // $montoPagado = $validatedData['monto_pagado'] ?? 0;
        // $saldo = max(0, $pagoTotal - $montoPagado);
        // Crear el pago
        $fechaInicio = Carbon::parse($validatedData['pag_inicio']);

        if (!empty($membresia->mem_durac)) {
            $fechaFin = $fechaInicio->copy()->addDays($membresia->mem_durac)->format('Y-m-d');
        } elseif (!empty($membresia->mem_limit)) {
            $fechaFin = Carbon::parse($membresia->mem_limit)->format('Y-m-d');
        } else {
            $fechaFin = $validatedData['pag_fin']; // último recurso
        }

        $pago=Pagos::create([
            'fkalum' => $validatedData['fkalum'],
            'fkuser' => $validatedData['fkuser'],
            'fksede' => $validatedData['fksede'],
            'fkmetodo' => $validatedData['fkmetodo'],
            'fkmem' => $validatedData['fkmem'],
            'tipo_membresia' => $tipoMembresia, // $membresia->tipo;
            'pago' => $validatedData['pago'],
            'pag_inicio' => $fechaInicio->format('Y-m-d'),
            'pag_fin' => $fechaFin,
            'estado_pago' => $estadoPago,
            'fecha_limite_pago' => $fechaLimitePago,
            'saldo_pendiente' => $saldoPendiente,
            'comision_ajustada' => $estadoPago === 'completo' ? ($membresia->mem_comi ?? 0) : 0,
            'monto_pagado' => $validatedData['monto_pagado'],
            'pag_entre'=>$validatedData['pag_entre'] ?? null
        ]);
        // Generar el detallle Pago
        if ($membresia->categoria_m->nombre_m !== 'Registro') {
            PagoDetalle::create([
                'monto' => $validatedData['monto_pagado'],
                'estado' => $estadoPago,
                'fkmetodo' => $validatedData['fkmetodo'],
                'fkpago' => $pago->id_pag,
                'fkmemb' => $membresia->id_mem
            ]);
        }
                $alumno = $pago->alumno;

        // Renovación
        if ($alumno && $alumno->membresiaVigente  && $membresia->categoria_m->nombre_m !== 'Registro' )  {
            Histopag::create([
                'fkpago' => $pago->id_pag,
                'fkuser' => $pago->fkuser,
                'fkmem' => $membresia->id_mem,
                'fkalum' => $alumno->id_alumno,
                'fecha_inicio' => $pago->pag_inicio,
                'fecha_fin' => $pago->pag_fin,
                'tipo_membresia' => $tipoMembresia,
                'estado_pago' => $estadoPago,
                'fksede' => $validatedData['fksede'],
                'tipo'=>'Renovacion'
            ]);
        }

        $redirect = redirect();
            if ($estadoPago === 'incompleto') {
                return $redirect->route('pagos.incompletos')
                ->with('estado', 'El pago fue actualizado correctamente.');
            } else {
                return $redirect->route('pagos.completos')
                ->with('estado', 'El pago fue actualizado correctamente.');
            }


    }

    /**
     * Display the specified resource.
     */
    public function show(String $pagos)
    {
        $pagos = Pagos::with(['alumno', 'membresia', 'metodo', 'sede', 'user'])
        ->findOrFail($pagos);

        return view('pagos.pagshow', [
            'pago' => $pagos,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pagos $pago, Request $request)
    {
        $alumnoId = $request->input('alumno_id');
        $users = User::withRolesAdminAndEmpleado();
        $alumno = Alumno::find($alumnoId);
        $fksedeAlumno = $alumno ? $alumno->fksede : null;
        $codigoAlumno = $alumno ? $alumno->alum_codigo : null;
        $membresiasActivas = Membresias::with(['categoria_m'])
            ->where('estado', 'A')
            ->orderBy('mem_nomb', 'asc')
            ->get();

        return view('pagos.pagedit', [
            'pago' => $pago,
            'membresia' => $membresiasActivas,
            'users' => $users,
            'sede' => Sede::all(),
            'metodo' => Metodo::all(),
            'montoActual' => $pago->pagodetalle()->sum('monto'),
            'codigoAlumno' => $codigoAlumno,
            'sedeAlumno' => $fksedeAlumno,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Pagos $pago,CreatePagosRequests $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is(User::ROL_ADMIN);

        $validatedData = $request->validated();

        $alumno = Alumno::find($validatedData['fkalum']);
        $membresia = Membresias::find($validatedData['fkmem']);

         if (!$membresia) {
             return back()->with('error', 'La membresía seleccionada no existe.');
         }

         $tipoMembresia = $membresia->tipo;

         // Fecha inicio y Fin
        $pagInicio = $isAdmin ? $validatedData['pag_inicio'] : $pago->pag_inicio;

        if ($isAdmin && !$pagInicio) {
            $pagInicio = $pago->pag_inicio;  
        }
        if (!$isAdmin && !$request->has('fecha_limite_pago')) {
            // Si no es admin y no se envió la fecha, mantener el valor actual
            $fechaLimitePago = $pago->fecha_limite_pago;
        } else {
            // Para administradores o cuando se envía explícitamente la fecha
            $fechaLimitePago = $request->input('fecha_limite_pago');
        }
        
        $fechaInicio = Carbon::parse($pagInicio);

        if ($request->filled('pag_update')) {
            $fechafin = Carbon::parse($request->input('pag_update'))->format('Y-m-d');
        } elseif (!empty($membresia->mem_durac)) {
            $fechafin = $fechaInicio->copy()->addDays($membresia->mem_durac)->format('Y-m-d');
        } elseif (!empty($membresia->mem_limit)) {
            $fechafin = Carbon::parse($membresia->mem_limit)->format('Y-m-d');
        } else {
            $fechafin = $pago->pag_fin; // mantener valor anterior si no hay nada
        }

         $estadoPago = $validatedData['estado_pago'];
         $estadoIncompleto = $estadoPago === 'incompleto';
//         $fechaLimitePago = $estadoIncompleto ? $request->input('fecha_limite_pago') : null;
         $saldoPendiente = $estadoIncompleto ? $validatedData['saldo_pendiente'] : 0;
        //  $montoPagado = $estadoIncompleto ? $validatedData['monto_pagado'] : 0;
        //  $saldoPendiente = $validatedData['saldo_pendiente'];

         $fechaLimitePago = null;
         if ($estadoIncompleto && $request->has('fecha_limite_pago')) {
             $fechaLimitePago = $request->input('fecha_limite_pago');
         }

         $comisionAjustada = $membresia->mem_comi ?? 0;
    
         if ($estadoIncompleto) {
             // Si el pago sigue siendo incompleto, comisión es 0
             $comisionAjustada = 0;
         } else {
             // Si cambia de incompleto a completo, calcular penalización
             if ($pago->estado_pago === 'incompleto' && $estadoPago === 'completo') {
                 // Calcular semanas de retraso desde la fecha límite original hasta ahora
                 if ($pago->fecha_limite_pago) {
                     $fechaLimiteOriginal = Carbon::parse($pago->fecha_limite_pago);
                     $fechaActual = Carbon::now();
                     
                     // Calcular semanas completas de retraso (solo si hay retraso)
                     if ($fechaActual->gt($fechaLimiteOriginal)) {
                         $semanasRetraso = $fechaLimiteOriginal->diffInWeeks($fechaActual);
                         
                         // Restar 5 por cada semana de retraso
                         $penalizacion = $semanasRetraso * 5;
                         $comisionAjustada = max(0, $comisionAjustada - $penalizacion);
                         
                         
                     }
                 }
             } else if ($pago->estado_pago === 'completo' && $estadoPago === 'completo') {
                 // Si ya era completo y sigue completo, mantener la comisión ajustada existente
                 $comisionAjustada = $pago->comision_ajustada;
             }
         }
        $totalPagado = $pago->pagodetalle()->sum('monto');
        $monto = $validatedData['estado_pago'] === 'completo' ? $validatedData['pago'] : $validatedData['monto_pagado'];


//        $oldFechaInicio = $pago->pag_inicio;
//        $oldFechaFin = $pago->pag_fin;
        $oldMembresiaId = $pago->fkmem;

         $pago->update([
             'fkalum' => $validatedData['fkalum'],
             'fkuser' => $validatedData['fkuser'],
             'fksede' => $validatedData['fksede'],
             'fkmetodo' => $validatedData['fkmetodo'],
             'fkmem' => $validatedData['fkmem'],
             'tipo_membresia' => $tipoMembresia,
             'pago' => $validatedData['pago'],
             'estado_pago' => $estadoPago,
             'fecha_limite_pago' => $fechaLimitePago,
             'saldo_pendiente' => $saldoPendiente,
             'monto_pagado' => $monto,
             'comision_ajustada' => $comisionAjustada,
             'pag_entre'=>$validatedData['pag_entre'] ?? null,
             'pag_fin' => $fechafin,
         ]);

         if ($isAdmin) {
             $pago->update([
                 'pag_inicio' => $pagInicio,
             ]);
         }

         // Registro pago detalles
        $saldo = $monto - $totalPagado;

//        $fechaInicioChanged = $oldFechaInicio != $fechaInicio->format('Y-m-d');
//        $fechaFinChanged = $oldFechaFin != $fechafin;
//
//        $renovacionCompleta = $fechaInicioChanged && $fechaFinChanged;
//
//        if ($renovacionCompleta) {
//            $montoRenovacion = $membresia->mem_cost;
//            PagoDetalle::create([
//                'monto' => $montoRenovacion,
//                'estado' => $validatedData['estado_pago'],
//                'fkmetodo' => $validatedData['fkmetodo'],
//                'fkpago' => $pago->id_pag,
//                'fkmemb' => $membresia->id_mem,
//                'tipo' => 'renovacion'
//            ]);
//        }

        if ($saldo > 0 ) {
            PagoDetalle::create([
                'monto' => $saldo,
                'estado' => $validatedData['estado_pago'],
                'fkmetodo' => $validatedData['fkmetodo'],
                'fkpago' => $pago->id_pag,
                'fkmemb' => $membresia->id_mem,
            ]);
        }

        // Actualización de membresia
        if ($pago->fkmem != $oldMembresiaId) {
            Histopag::create([
                'fkpago' => $pago->id_pag,
                'fkuser' => $user->id,
                'fkmem' => $membresia->id_mem,
                'fkalum' => $alumno->id_alumno,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechafin,
                'tipo_membresia' => $tipoMembresia,
                'estado_pago' => $estadoPago,
                'fksede'=>$validatedData['fksede'],
                'tipo'=>'Actualizacion'
            ]);
        }

        $redirect = redirect();

            if ($estadoPago === 'incompleto') {
                return $redirect->route('pagos.incompletos')
                ->with('estado', 'El pago fue actualizado correctamente.');
            }else{
                return $redirect->route('pagos.completos')
                ->with('estado', 'El pago fue actualizado correctamente.');
            }

    }

    public function congelarMembresia($idPago)
    {
        $pago = Pagos::findOrFail($idPago);


        $fechaActual = now();
        $fechaFin = Carbon::parse($pago->pag_fin);
        $diasPendientes = $fechaActual->diffInDays($fechaFin);


        $pago->update([
            'congelado' => 1,
            'fecha_congelacion' => $fechaActual,
            'dias_pendientes' => $diasPendientes,
        ]);

        return redirect()
                ->back()
                ->with('info', 'La membresía ha sido congelada.');
    }


    public function reanudarMembresia($idPago)
    {
        $pago = Pagos::findOrFail($idPago);


        $fechaActual = now();
        $nuevaFechaFin = $fechaActual->copy()->addDays($pago->dias_pendientes);


        $pago->update([
            'congelado' => 0,
            'pag_inicio' => $fechaActual,
            'pag_fin' => $nuevaFechaFin,
            'fecha_congelacion' => null,
            'dias_pendientes' => 0,
        ]);

        return redirect()
                ->back()
                ->with('estado', 'La membresía ha sido reanudada.');
    }

    public function destroy(string $id)
    {
        //
    }
}
