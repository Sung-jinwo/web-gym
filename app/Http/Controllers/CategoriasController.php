<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\User;
use App\Http\Requests\CreateCategoriasRequest;


class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoria = Categoria::latest()->paginate(3);

        return view('vencate.categovi',compact('categoria'));
    }


    public function create()
    {
        return view('vencate.categocreate',[
            'categoria'=> new Categoria(),
            'user'=> User::all()
        ]);
    }


    public function store(CreateCategoriasRequest $request)
    {
        $categoria = new Categoria ($request->validated());
        $categoria->save();

        return redirect()
                ->route('producto.index')
                ->with('estado','Se Creo una nueva Categoria');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit(Categoria $categoria)
    {
        return view('vencate.categoedit',[
            'categoria' => $categoria,
            'user'=> User::all()
        ]);
    }


    public function update(Categoria $categoria,CreateCategoriasRequest $request)
    {
        $validatedData = $request->validated();

        $categoria->update(array_filter($validatedData));

        return redirect()
                ->route('categoria.index')
                ->with('estado', 'El Prodcuto fue actualizado correctamente');

    }


    public function destroy($id)
    {
        //
    }
}
