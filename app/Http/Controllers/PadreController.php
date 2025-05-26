<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Padres;
use App\Models\Alumno;

class PadreController extends Controller
{
    public function index()
    {
        $padres = Padres::with('alumno')->get();
        return view('padres.padrvi', compact('padres'));
    }

    // Mostrar formulario para crear un nuevo padre
    public function create(Request $request)
    {
        $alumnoId = $request->query('alumno_id');
        $alumno = Alumno::findOrFail($alumnoId);

        if ($alumno->alum_eda >= 18) {
            return redirect()->back()->withErrors(['error' => 'El alumno debe ser menor de 18 años para agregar un padre.']);
        }

        return view('padres.padrcreate', [
            'alumno' => $alumno,
            'action' => route('padres.store'),
            'btnText' => 'Crear Padre',
        ]);
    }

    // Guardar un nuevo padre
    public function store(Request $request)
    {
        $request->validate([
            'padre_nombre' => 'required|string|max:255',
            'padre_apellido' => 'required|string|max:255',
            'padre_telefono' => 'required|string|max:15',
            'padre_correo' => 'nullable|email|max:255',
            'fksexo' => 'required|in:1,2', // Validar que el sexo sea 1 (Masculino) o 2 (Femenino)
            'fkalumno' => 'required|exists:alumno,id_alumno',
        ]);
    
        $alumno = Alumno::find($request->fkalumno);
        if ($alumno->alum_eda >= 18) {
            return redirect()->back()->withErrors(['fkalumno' => 'El alumno debe ser menor de 18 años.']);
        }
    
        Padres::create($request->all());
    
        return redirect()
                ->route('padres.index')
                ->with('estado', 'Un Padre fue creado exitosamente.');
    }

    // Mostrar formulario para editar un padre
    public function edit($id)
    {
        $padre = Padres::findOrFail($id);
        $alumno = $padre->alumno; // Solo alumnos mayores de 18
        return view('padres.padredit', [
            'padre' => $padre,
            'alumno' => $alumno,
            'action' => route('padres.update', $padre),
            'btnText' => 'Actualizar Padre',
        ]);
    }

    // Actualizar un padre
    public function update(Request $request, $id)
    {
        $request->validate([
            'padre_nombre' => 'required|string|max:255',
            'padre_apellido' => 'required|string|max:255',
            'padre_telefono' => 'required|string|max:15',
            'padre_correo' => 'nullable|email|max:255',
            'fksexo' => 'required|in:1,2', // Validar que el sexo sea 1 (Masculino) o 2 (Femenino)
            'fkalumno' => 'required|exists:alumno,id_alumno',
        ]);
    
        $padre = Padres::findOrFail($id);
        $alumno = Alumno::find($request->fkalumno);
    
        if ($alumno->alum_eda >= 18) {
            return redirect()->back()->withErrors(['fkalumno' => 'El alumno debe ser menor de 18 años.']);
        }
    
        $padre->update($request->all());
    
        return redirect()
                ->route('padres.index')
                ->with('estado','' .$padre->padre_nombre.' '.$padre->padre_apellido.' fue actualizado exitosamente.');
    }

    // Eliminar un padre
    public function destroy($id)
    {
        $padre = Padres::findOrFail($id);
        $padre->delete();
        return redirect()
            ->route('padres.index')
            ->with('estado', ''.$padre->padre_nombre. 'Padre eliminado exitosamente.');
    }
}
