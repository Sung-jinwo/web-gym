<?php

namespace App\Http\Controllers;


use App\Models\Alumno;
use App\Models\Membresias;
use App\Models\Pagos;
use App\Models\Padres;
use App\Models\sede;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateServicioRequest;
use Intervention\Image\ImageManager; // Importa ImageManager
use Intervention\Image\Encoders\JpegEncoder;
// use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\Request;

class AlumnoContoller extends Controller
{

    public function index(Request $request)
{
    $sedes = sede::all();

    $user = Auth::user();

    $alumnoTexto = $request->get('alumnoTexto');
    $estado = $request->get('estado', 'A');
    $idSede = $request->input('id_sede');
    $fechaFiltro = $request->get('fecha_filtro');

    $query = Alumno::with(['pagos.membresia', 'padres']);

    if ($estado) {
        $query->where('alum_estado', $estado);
    }

    if ($user->is(User::ROL_ADMIN) && $idSede) {
        $query->where('fksede', $idSede);
    } elseif ($user->is(User::ROL_EMPLEADO)) {
        $query->where('fksede', $user->fksede);
    }

    if ($fechaFiltro) {
        $hoy = now();

        $query->whereHas('pagos', function ($q) use ($fechaFiltro, $hoy) {
            $q->where('tipo_membresia', 'principal');

            if ($fechaFiltro == 'vigente') {
                $q->where('pag_fin', '>', $hoy->copy()->addDays(5));
            } elseif ($fechaFiltro == 'por_caducar') {
                $q->where('pag_fin', '>=', $hoy)
                  ->where('pag_fin', '<=', $hoy->copy()->addDays(5));
            } elseif ($fechaFiltro == 'vencido') {
                $q->where('pag_fin', '<', $hoy);
            }
        });
    }

    if ($alumnoTexto) {
        $query->where(function ($q) use ($alumnoTexto) {
            $q->where('alum_codigo', $alumnoTexto)
              ->orWhere('alum_nombre', 'LIKE', '%' . $alumnoTexto . '%')
              ->orWhere('alum_apellido', 'LIKE', '%' . $alumnoTexto . '%')
              ->orWhere(Alumno::raw("CONCAT(alum_nombre, ' ', alum_apellido)"), 'LIKE', '%' . $alumnoTexto . '%')
              ->orWhere('alum_telefo', 'LIKE', '%' . $alumnoTexto . '%');
        });
    }

    $query->when($request->estado_pago, function ($query, $estado_pago) {
        $query->whereHas('pagos', function ($q) use ($estado_pago) {
            $q->where('estado_pago', $estado_pago)->where('tipo_membresia', 'principal');
        });
    });

    $alumnos = $query->orderByDesc('alum_codigo')
                    ->paginate(7);

    return view('alumno.alumvi', [
        'alumnos' => $alumnos,
        'estado' => $estado,
        'fecha_filtro' => $fechaFiltro,
        'sedes' => $sedes
    ]);
}

    public function buscarPorCodigo(Request $request)
    {
        $codigo = $request->query('codigo');

        // Validar que el código tenga exactamente 4 dígitos
        if (!$codigo || strlen($codigo) !== 4) {
            return response()->json(['success' => false, 'message' => 'Código inválido']);
        }

        // Buscar el alumno por su código
        $alumno = Alumno::where('alum_codigo', $codigo)->first();

        if ($alumno) {
            return response()->json([
                'success' => true,
                'alumno' => [
                    'id_alumno' => $alumno->id_alumno,
                    'nombre_completo' => $alumno->alum_nombre . ' ' . $alumno->alum_apellido,
                ],
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Alumno no encontrado']);
    }


    //prueba de busqueda===========
    public function buscarAlumnoPorId(Request $request)
    {
        $id = $request->get('id');
        $alumno = Alumno::find($id);

        if ($alumno) {
            return response()->json([
                'id_alumno' => $alumno->id_alumno,
                'alum_codigo' => $alumno->alum_codigo,
                'nombre_completo' => $alumno->alum_nombre . ' ' . $alumno->alum_apellido
            ]);
        } else {
            return response()->json([
                'error' => 'Alumno no encontrado'
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('alumno.alumcreate',[
            'alumno'=> new Alumno(),
            'sedes' => sede ::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateServicioRequest $request)
    {
        $validatedData = $request->validated();

        $alum_codigo = $validatedData['alum_codigo'];


        if (Alumno::where('alum_codigo', $alum_codigo)->exists()) {
            return redirect()->back()->withErrors(['alum_codigo' => 'El código de alumno ya existe.'])->withInput();
        }
        $alumno = new Alumno($validatedData);
//        $alumno->fech_registro = now();
        if ($request->hasFile('alum_img')) {
            $imagePath = $request->file('alum_img')->store('images', 'public');
            $alumno->alum_img = $imagePath;
        }


        $alumno->save();

        return redirect()
                    ->route('alumno.index')
                    ->with('estado','Se a creado un nuevo alumno correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $alumno)
    {
        $alumno = Alumno::with(['pagos.membresia', 'padres'])->find($alumno);

        if (!$alumno) {
            return redirect()
                ->route('alumno.index')
                ->with('error', 'Alumno no encontrado');
        }

        $membresiasAdicionales = $alumno->pagos()
            ->where('tipo_membresia', 'adicional')
            ->get();

        return view('alumno.alumshow', [
            'alumno' => $alumno,
            'membresiaPrincipal' => $alumno->membresiaVigente,
            'membresiasAdicionales' => $membresiasAdicionales,
            'tienePadres' => $alumno->padres->isNotEmpty(),
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumno $alumno)
    {
        // dd($alumno->fecha_inicio, $alumno->fecha_finalizacion);

        $necesitaAgregarPadre = $alumno->alum_eda > 18 && $alumno->padres->isEmpty();
        return view('alumno.alumedit',[
            'alumno' => $alumno,
            'sedes' => sede::all(),
            'necesitaAgregarPadre' => $necesitaAgregarPadre,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Alumno $alumno, CreateServicioRequest $request)
    {
        $validatedData = $request->validated();

        if (!isset($validatedData['alum_codigo'])) {
            return redirect()->back()->withErrors(['alum_codigo' => 'El código de alumno es requerido.']);
        }
//        $alumno->fech_registro = now();

        if ($request->hasFile('alum_img')) {
            if ($alumno->alum_img && Storage::exists($alumno->alum_img)) {
                Storage::delete($alumno->alum_img);
            }
            $imagePath = $request->file('alum_img')->store('images', 'public');
            $alumno->alum_img = $imagePath;

            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::get($alumno->alum_img));
            $image->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $encodedImage = $image->encode(new JpegEncoder());
            Storage::put($alumno->alum_img, $encodedImage);
            $validatedData['alum_img'] = $imagePath;
        }

        $alumno->update(array_filter($validatedData));

        // Redireccionar con un mensaje de éxito
        return redirect()
                    ->route('alumno.show', $alumno)
                    ->with('estado', 'El Alumno fue actualizado correctamente');
    }

    public function cambiarEstado(Alumno $alumno)
    {
        if ($alumno->alum_estado == 'A') {
            $alumno->update(['alum_estado' => 'E']);
            $mensaje = 'El alumno fue desactivado correctamente';
        } elseif ($alumno->alum_estado == 'E') {
            $alumno->update(['alum_estado' => 'A']);
            $mensaje = 'El alumno fue activado correctamente';
        }
        return redirect()->route('alumno.index')->with('estado', $mensaje);
    }


    public function destroy(Alumno $alumno)
    {
//        $alumno->asistencia()->delete();

        if ($alumno->asistencia()->exists()) {
            return redirect()->route('alumno.index')
                ->with('error', 'No se puede eliminar el alumno porque tiene asistencias registradas.');
        }

        if ($alumno->padres()->exists()){
            return redirect()->route('alumno.index')
                ->with('error', 'No se puede eliminar el alumno porque tiene Padres registrados.');
        };


        Storage::delete($alumno->alum_img);

        $alumno->delete();


        return redirect()->route('alumno.index')
                         ->with('estado', 'El alumno fue eliminado Correctamente');
    }

    public function boletaPdf(Alumno $alumno)
    {
        $lastMembresia = $alumno->membresiaVigente;
        if (!$lastMembresia) {
            return back();
        }

        $lastDetalle = $lastMembresia->pagodetalle()
            ->orderByDesc('created_at')
            ->first();

        $fechaActual = $lastDetalle->pago_formato;
        $horaActual = $lastDetalle->pago_formato_hora;
        $pdf = Pdf::loadView('alumno.alumboletapdf', [
            'lastMembresia' => $lastMembresia,
            'lastDetalle' => $lastDetalle,
            'fecha_actual' => $fechaActual,
            'hora_actual' => $horaActual,
        ]);

        return $pdf->stream('boleta.pdf'); // stream: para ver pdf | download: descargar
    }

    public function contratoPdf(Alumno $alumno)
    {

        $lastMembresia = $alumno->membresiaVigente;
        if (!$lastMembresia) {
            return back();
        }

        $lastDetalle = $lastMembresia->pagodetalle()
            ->orderByDesc('created_at')
            ->first();

        $padre=$alumno->padres()
            ->first();

        $pdf = Pdf::loadView('alumno.alumcontratopdf', [
            'lastMembresia' => $lastMembresia,
            'lastDetalle' => $lastDetalle,
            'padre' => $padre,

        ]);

        return $pdf->stream('boleta.pdf');
    }


}
