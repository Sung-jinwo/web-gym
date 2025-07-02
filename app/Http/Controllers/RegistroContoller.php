<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMensajeRequest;
use App\Http\Requests\CreateServicioRequest;
use App\Models\Alumno;
use App\Models\Mensaje;
use App\Models\Sede;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroContoller extends Controller
{

    public function index(Request $request)
    {
        $sedes = Sede::all();
        $user = Auth::user();

        $filtroEstado = $request->get('alum_estado', 'A');
        $idSede = $request->input('id_sede');
        $alumnoTexto = $request->get('alumnoTexto');
        $fechaFiltro = $request->input('fecha_filtro', Carbon::now()->format('Y-m'));
        $query =  Mensaje::with('alumno');

        if ($filtroEstado) {
            $query->whereHas('alumno', function ($q) use ($filtroEstado) {
                $q->where('alum_estado', $filtroEstado);
            });
        }


        if ($user->is(User::ROL_ADMIN) && $idSede) {
            $query->where('fksede', $idSede);
        } elseif ($user->is(User::ROL_EMPLEADO)) {
            $query->where('fksede', $user->fksede);
        }

            if ($alumnoTexto) {
            $query->whereHas('alumno',function ($q) use ($alumnoTexto) {
                $q->where('alum_telefo', $alumnoTexto)
                    ->orWhere('alum_nombre', 'LIKE', '%' . $alumnoTexto . '%')
                    ->orWhere('alum_apellido', 'LIKE', '%' . $alumnoTexto . '%')
                    ->orWhere(Alumno::raw("CONCAT(alum_nombre, ' ', alum_apellido)"), 'LIKE', '%' . $alumnoTexto . '%');
            });
        }

        if ($fechaFiltro){
            $fecha = Carbon::parse($fechaFiltro);
            $query->whereYear('mensa_edit', $fecha->year)
                  ->whereMonth('mensa_edit', $fecha->month);
        }

        $alumnos = $query->paginate(7)->appends([
                                'alum_estado' => $filtroEstado,
                                'fecha_filtro' => $fechaFiltro,
                            ]);

        foreach ($alumnos as $alumno) {
            $alumno->color_class = $alumno->color_cierre;
        }

        return view('alumno_registrar.registervi',
            compact('alumnos', 'filtroEstado', 'sedes','fechaFiltro'

            ));
    }


    public function create()
    {
        return view('alumno_registrar.registercreate',[
            'registros'=> new Alumno(),
            'sedes' => sede ::all()
        ]);
    }



    public function store(CreateServicioRequest $request)
    {
        $validatedData = $request->validated();

        $alumno = new Alumno($validatedData);
        $alumno->save();


        // Mensaje::create([
        //     'fkalum' => $alumno->id_alumno,
        // ]);

        $mensaje = new Mensaje();
        $mensaje->fkalum = $alumno->id_alumno;
        $mensaje->fksede = $alumno->fksede;
        // apara guardar la fecha actual en la variable mensa_edit
        $mensaje->mensa_edit = Carbon::now()->toDateString();
        $mensaje->save();
        // dd($alumno);

        return redirect()
            ->route('registro.index')
            ->with('estado','Se a creado un nuevo Prospecto correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumno $registros)
    {

        if (!$registros) {
            return redirect()
                ->route('registro.index')
                ->with('error', 'Prospecto no encontrado');
        }


        return view('alumno_registrar.registershow', [
            'registros' => $registros,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumno $registros)
    {
        return view('alumno_registrar.registeredit',[
            'registros' => $registros,
            'sedes' => Sede::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Alumno  $registros, CreateServicioRequest $request)
    {
        $validatedData = $request->validated();

        $sedeAnterior = $registros->fksede;


        $registros->update(array_filter($validatedData));


        if ($registros->fksede != $sedeAnterior) {
            $mensaje = $registros->mensaje;
            if ($mensaje) {
                $mensaje->fksede = $registros->fksede;
                $mensaje->save();
            }
        }


        return redirect()
            ->route('registro.index', $registros)
            ->with('estado', 'El Prospecto fue actualizado correctamente');
    }

    public function conversar(Mensaje $registros)
    {

        return view('alumno_registrar.registerconver',[
            'registros'=> $registros,
        ]);
    }

    public function mensaje(Mensaje $registros, CreateMensajeRequest $request)
    {
        // dd($request->all());

        $validatedData = $request->validated();

        if (isset($validatedData['postergar']) && $validatedData['postergar'] === 'Si') {
            if (!empty($validatedData['mensa_edit'])) {
                $nuevaFecha = Carbon::createFromFormat('Y-m', $validatedData['mensa_edit'])->startOfMonth();
                if (is_null($registros->mensa_edit) ||
                    !$nuevaFecha->isSameDay(Carbon::parse($registros->mensa_edit)->startOfMonth())) {
                    $validatedData['mensa_edit'] = $nuevaFecha;
                } else {
                    unset($validatedData['mensa_edit']);
                }
            } else {
                unset($validatedData['mensa_edit']);
            }
        } else {
            unset($validatedData['mensa_edit']);
            $validatedData['postergar'] = 'No';
        }

        

        $registros->update($validatedData);
        return redirect()
            ->route('registro.index', $registros)
            ->with('estado', 'El Mensaje fue Enviado correctamente');
    }



    public function destroy(Alumno $alumno)
        {
            // Eliminar los mensajes relacionados primero
            $alumno->mensaje()->delete();

            // Luego eliminar al alumno
            $alumno->delete();

            return redirect()->route('registro.index')
                ->with('estado', 'El Prospecto fue eliminado correctamente');
        }


    public function estado(Alumno $registros)
    {
        if ($registros->alum_estado == 'A') {
            $registros->update(['alum_estado' => 'E']);
            $mensaje = 'El alumno fue desactivado correctamente';
        } elseif ($registros->alum_estado == 'E') {
            $registros->update(['alum_estado' => 'A']);
            $mensaje = 'El alumno fue activado correctamente';
        }
        return redirect()->route('registro.index')->with('estado', $mensaje);
    }
}
