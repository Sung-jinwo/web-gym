<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGastosRequest;
use App\Models\Gastos;
use App\Models\Sede;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastosController extends Controller
{
    public function index(Request $request)
    {
        $sedes = Sede::all();
        $user = Auth::user();

        $categoria = $request->get('categoria');
        $idSede = $request->input('id_sede');
//        $categoriaTexto = $request->get('categoria');
        $fechaFiltro = $request->input('fecha_filtro', Carbon::now()->format('Y-m-d'));
        $query =  Gastos::query();


        if (($user->is(User::ROL_ADMIN) || $user->is(User::ROL_VENTAS)) && $idSede) {
            $query->where('fksede', $idSede);
        } elseif ($user->is(User::ROL_EMPLEADO)) {
            $query->where('fksede', $user->fksede);
        }

        if ($categoria) {
            $query->where('gast_categoria', $categoria);
        }


//        if ($categoriaTexto) {
//            $query->where(function ($q) use ($categoriaTexto) {
//                $q->where('alum_codigo', $categoriaTexto)
//                    ->orWhere('alum_nombre', 'LIKE', '%' . $categoriaTexto . '%')
//                    ->orWhere('alum_apellido', 'LIKE', '%' . $categoriaTexto . '%')
//                    ->orWhere('alum_telefo', 'LIKE', '%' . $categoriaTexto . '%');
//            });
//        }


        if ($fechaFiltro) {
            $fecha = Carbon::parse($fechaFiltro);
            $query->whereDate('created_at', $fecha);
        }

        $gastos = $query->orderByDesc('updated_at')
                        ->paginate(7)
                        ->appends(request()->query());

        return view('gasto.gastovi',
               compact( 'gastos','sedes', 'fechaFiltro','categoria'
                   ));
    }

    public function create(){

        $users = User::withRolesAdminAndEmpleado();

        return view('gasto.gastocreate',[
            'gastos'=> new Gastos(),
            'sedes' => sede ::all(),
            'users' => $users
        ]);
    }


    public function store(CreateGastosRequest $request){


        $validatedData = $request->validated();

        $gastos = new Gastos($validatedData);
        $gastos->save();



        return redirect()
            ->route('gasto.index')
            ->with('estado','Se Agrego un nuevo gasto');
    }


    public function show(Gastos $gastos){
        if(!$gastos){
            return redirect()
                ->route('gasto.index')
                ->with('error', 'Gasto no encontrado');
        }

        return view('gasto.gastoshow',
               compact('gastos'));

    }
    public function edit(Gastos $gastos){
        $users = User::withRolesAdminAndEmpleado();

        return view('gasto.gastoedit',[
           'gastos' => $gastos,
            'users' => $users,
            'sedes' => sede ::all(),
        ]);
    }
    public function update(CreateGastosRequest $request,Gastos $gastos){

        $validatedData = $request->validated();
        $gastos->update($validatedData);


        return redirect()
            ->route('gasto.show', $gastos)
            ->with('estado', 'El gasto fue actualizado correctamente');

    }



    public function destroy(Gastos $gastos){
        $gastos->delete();
        return redirect()->route('gasto.index')
            ->with('estado', 'El Gasto fue eliminado Correctamente');
    }

}
