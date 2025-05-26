<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ctegoria_m;
use App\Models\User;
use App\Http\Requests\CreateCategoriaMRequest;

// requests


class Categori_MController extends Controller
{

    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('membre_cat.catmcreate',[
            'categoria_m'=> new Ctegoria_m(),
            'user'=> User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoriaMRequest $request)
    {
        // dd($request->all());
        $categoria_m = new Ctegoria_m ($request->validated());
        $categoria_m->save();     
        
        return redirect()
                ->route('membresias.index')
                ->with('estado','Se Creo una nueva Categoria');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ctegoria_m $categoria_m)
    {
        return view('membre_cat.catmedit',[
            'categoria_m' => $categoria_m,
            'user'=> User::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Ctegoria_m $categoria_m,CreateCategoriaMRequest $request)
    {
        $validatedData = $request->validated(); 
        
        $categoria_m->update(array_filter($validatedData));

        return redirect()->route('membresias.index')
                    ->with('estado', 'El Prodcuto fue actualizado correctamente');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ctegoria_m $categoria_m)
    {
        $categoria_m->delete();
        
        return redirect()->route('membresias.index')
                         ->with('estado', 'La membresia fue eliminado Correctamente');
    }
}
