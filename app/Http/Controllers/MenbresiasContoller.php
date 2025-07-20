<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Categoria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Membresias;
use App\Models\Histomem;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Ctegoria_m;
use App\Http\Requests\CreateMembresiaRequest;


class MenbresiasContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categorias = Ctegoria_m::all();

        $filtroEstado = $request->get('estado', 'A');
        $fechaFiltro = $request->input('fecha_filtro');
        $id_categoria = $request->input('id_categoria');
        $membresiaTexto = $request->get('membresiaTexto');

        $query =  Membresias::with('categoria_m');


        if ($filtroEstado) {
            $query->where('estado', $filtroEstado);
        }

        if ($fechaFiltro){
            $fecha = Carbon::parse($fechaFiltro);
            $query->whereYear('created_at', $fecha->year)
                ->whereMonth('created_at', $fecha->month);
        }

        if ($id_categoria) {
            $query->where('fkcategoria', $id_categoria);
        }

        if ($membresiaTexto) {
            $query->where('mem_nomb', 'LIKE', '%' . $membresiaTexto . '%');
        }


        $membresias = $query->orderByDesc('updated_at')
                    ->paginate(7)->appends([
                        'estado' => $filtroEstado,
                        'membresiaTexto' => $membresiaTexto
                    ]);

        return view('categoria.categvi',
            compact('membresias', 'filtroEstado','categorias','fechaFiltro'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoria.categcreate',[
            'membresias'=> new Membresias(),
            'categoria_m'=> Ctegoria_m::all(),
            'sedes'=> Sede::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMembresiaRequest $request)
    {
//        dd($request->all());

//        $user = Auth::user(); // Usuario logeado
//
//        if (!$user->is(User::ROL_ADMIN)) {
//            $request['fksede'] = $user->fksede;

//        }
        $validatedData = $request->validated();

        $membresia = new Membresias ($validatedData);
        $membresia->save();



        return redirect()
                ->route('membresias.index')
                ->with('estado','Se Agrego una nueva membresia');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $membresias)
    {
        return view('categoria.categshow',[
            'membresias'=> Membresias::with(['categoria_m'])->find($membresias),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Membresias $membresias)
    {
        //categedit
        return view('categoria.categedit',[
            'membresias' => $membresias,
            'categoria_m'=> Ctegoria_m::all(),
            'sedes'=> Sede::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Membresias $membresias,CreateMembresiaRequest $request)
    {
        $costoAnterior = $membresias->mem_cost;

        $validatedData = $request->validated();
        $membresias->update($validatedData);

        if ($costoAnterior != $membresias->mem_cost) {
            Histomem::create([
                'fkmem' => $membresias->id_mem, // ID de la membresía actualizada
                'fkuser' => auth()->id(), // ID del usuario que realizó la acción
                'fecha_edicion' => now(), // Fecha actual
                'costo_anterior' => $costoAnterior, // Costo antes del cambio
                'costo_nuevo' => $membresias->mem_cost, // Nuevo costo después del cambio
            ]);
        }

        return redirect()
                ->route('membresias.show', $membresias)
                ->with('estado', 'La membresia fue actualizado correctamente');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membresias $membresias)
    {
//        Eliminar con todo Categoria
//        Storage::delete($membresias->categoria_m);

        $membresias->delete();

        return redirect()->route('membresias.index')
            ->with('estado', 'La membresias fue eliminado Correctamente');
    }


    public function cambiarEstado(Membresias $membresias)
    {
        if ($membresias->estado == 'A') {
            $membresias->update(['estado' => 'E']);
            $mensaje = 'La membresias fue desactivada correctamente';
        } elseif ($membresias->estado == 'E') {
            $membresias->update(['estado' => 'A']);
            $mensaje = 'La membresias fue activado correctamente';
        }
        return redirect()->route('membresias.index')->with('estado', $mensaje);
    }
}
