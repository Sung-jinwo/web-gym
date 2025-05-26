<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;

class DetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detalle = DetalleVenta::with(['venta','producto'])->latest()->paginate(7);

        return view('detalle_ven.detallevi',compact('detalle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($detalle)
    {
        $detalle = DetalleVenta::with(['venta','producto'])->find($detalle);
        return view('detalle_ven.detalleshow', compact('detalle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function boletaPdf( DetalleVenta $detalle)
    {
        $detalleventa= $detalle->venta()
            ->orderByDesc('created_at')
            ->first();
        $deatlleproducto = $detalle->producto()
            ->orderByDesc('created_at')
            ->first();


        $fechaActual = $detalle->detalle_venta_formato;
        $horaActual = $detalle->detalle_venta_formato_hora;
        $pdf = Pdf::loadView('detalle_ven.detalleboleta', [
            'detalleventa'=>$detalleventa,
            'detalle' => $detalle,
            'deatlleproducto'=>$deatlleproducto,
            'fecha_actual' => $fechaActual,
            'hora_actual' => $horaActual,

        ]);

        return $pdf->stream('boleta.pdf');
    }
}
