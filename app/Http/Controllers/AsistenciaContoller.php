<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Asistensias;
use App\Models\Pagos;
use App\Models\sede;
use App\Models\User;
use App\Models\Alumno;
use App\Http\Requests\CreateAsistenciaRequest;
use Illuminate\Support\Facades\Auth;

class AsistenciaContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $sedes = sede::all();

        $user = Auth::user();
        $idSede = $request->input('sede_id');
        $alumnoTexto = $request->get('alumnoTexto');
        $fechaFiltro = $request->input('fecha_filtro', Carbon::now()->format('Y-m'));

        $query = Asistensias::with(['alumno', 'sede', 'user']);


        if ($user->is(User::ROL_ADMIN) && $idSede) {
            $query->where('fksede', $idSede);
        } elseif ($user->is(User::ROL_EMPLEADO)) {
            $query->where('fksede', $user->fksede);
        }

        if ($request->filled('alumnoTexto')) {
            $texto = $request->alumnoTexto;
            $query->whereHas('alumno', function($q) use ($texto) {
                $q->where('alum_codigo', $texto)
                    ->orWhere('alum_nombre', 'LIKE', "%$texto%")
                    ->orWhere('alum_apellido', 'LIKE', "%$texto%")
                    ->orWhereRaw("CONCAT(alum_nombre, ' ', alum_apellido) LIKE ?", ["%$texto%"])
                    ->orWhere('alum_telefo', 'LIKE', "%$texto%");
            });
        }
        if ($fechaFiltro){
            $fecha = Carbon::parse($fechaFiltro);
            $query->whereYear('created_at', $fecha->year)
                ->whereMonth('created_at', $fecha->month);
        }

        $asistencias = $query->orderBy('created_at', 'desc')->paginate(10)->appends([
                                'fecha_filtro' => $fechaFiltro
            ]);

        return view('asistencia.asisvi',
            compact('sedes', 'alumnoTexto', 'fechaFiltro', 'asistencias'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $alumnoId = $request->input('alumno_id');

        $alumno = Alumno::find($alumnoId);
        $codigoAlumno = $alumno ? $alumno->alum_codigo : null;

        return view('asistencia.asiscreate',[
            'asistencia'=> new Asistensias(),
            'alumno' => Alumno::all(),
            'sedes' => sede::all(),
            'user' => User::all(),
            'codigoAlumno' => $codigoAlumno,
        ]);
    }


    public function buscarAlumno(Request $request)
    {
        $codigo = $request->input('buscar');
        $alumno = Alumno::where('alum_codigo', $codigo)->first();

        if ($alumno) {
            return response()->json([
                'nombre_completo' => $alumno->alum_nombre . ' ' . $alumno->alum_apellido,
                'id_alumno' => $alumno->id_alumno // Devolver el ID del alumno
            ]);
        } else {
            return response()->json([
                'nombre_completo' => 'Alumno no encontrado',
                'id_alumno' => null // Devolver null si no se encuentra el alumno
            ]);
        }
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAsistenciaRequest $request)
    {
        // dd($request->all());
        // fkalumno
        $idAlum = $request->input('fkalum');

        $alumno = Alumno::findOrFail($idAlum);

        $ultimaMem = $alumno->membresia_vigente;

        if (!$ultimaMem) {
            return back()->with('error', 'Este usuario no puede registrar asistencias');
        }

        if ($ultimaMem->congelado == 1) {
            return back()
                ->with('error', 'No se puede registrar asistencia, tu pago esta congelado');
        }
        // fecha limite de pago > a la fecha de hoy back

        if ($ultimaMem->fecha_limite_pago && $ultimaMem->fecha_limite_pago > now()->toDateString()) {
            return back()
                ->with('error', 'No se puede registrar asistencia, tu fecha de pago esta caducado');
        }

        //pago de fecha-fin es mayor a la fecha de ahora
        if ($ultimaMem->pag_fin < now()->toDateString()) {
            return back()
                ->withInput()
                ->with('error', 'No se puede registrar asistencia: el pago está vencido (fecha límite: ' . $ultimaMem->pag_fin . ')');
        }

        $membresia = $ultimaMem->membresia;

        if ($membresia->mem_durac <= 90 && $alumno->fksede != $request->input('fksede')) {
            return back()
                ->withInput()
                ->with('error', 'No se puede registrar asistencia diferente a tu sede ('.$alumno->sede->sede_nombre.')');
        }



        $asistencia = new Asistensias ($request->validated());

        if (!$request->has('visi_fecha')) {
            $asistencia->visi_fecha = now();
        }

        $asistencia->save();

        return redirect()
                ->route('asistencia.show',['asistencia' => $asistencia])
                ->with('estado','Se Creo una nueva asistencia');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $asistencia)
    {

         $asistencia = Asistensias::with(['alumno.pagos', 'user', 'sede'])->find($asistencia);

         $membresiaPrincipal = $asistencia->alumno->pagos()
         ->where('tipo_membresia', 'principal')
         ->latest()
         ->first();

         return view('asistencia.asisshow', [
            'asistencia' => $asistencia,
            'membresiaPrincipal' => $membresiaPrincipal,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asistensias $asistencia)
    {
        return view('asistencia.asisedit',[
            'asistencia' => $asistencia,
            'pagos' => Pagos::all(),
            'alumno' => Alumno::all(),
            'sedes' => sede::all(),
            'user' => User::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Asistensias $asistencia,CreateAsistenciaRequest $request)
    {
        $validatedData = $request->validated();
        $asistencia->update($validatedData);
        return redirect()
            ->route('asistencia.show', $asistencia)
            ->with('estado', 'La Asistencia fue actualizado correctamente');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
