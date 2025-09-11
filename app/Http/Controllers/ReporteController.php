<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\IngresosDiariosPdfExport;
use App\Exports\InventarioExport;
use App\Exports\AlumnosExport;
use App\Exports\PagosExport;
use App\Exports\AsistenciasExport;
use App\Exports\VentasExport;
use App\Exports\PagosVentasExport;
use App\Models\PagoDetalle;
use App\Models\DetalleVenta;
use App\Models\User;
use App\Models\Metodo;
use Illuminate\Support\Facades\Auth;



use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function index(Request $request){
        $sedes = Sede::all();
        $user = Auth::user();
        $metodos_pagos = Metodo::all();


        $fechaFiltro = $request->get('fecha_filtro',Carbon::now()->format('Y-m-d'));
        $id_sede = $request->input('id_sede');
        $metodo_pago_seleccionado = $request->input('metodos_pagos');
        $query = PagoDetalle::with(['pago', 'metodo','membresia']);

        if ($user->is(User::ROL_ADMIN) && $id_sede) {
            $query->whereHas('pago', function ($q) use ($id_sede) {
                $q->where('fksede', $id_sede);
            });
        }

        if($metodo_pago_seleccionado){
            $query->whereHas('pago', function ($q) use ($metodo_pago_seleccionado) {
                $q->where('fkmetodo', $metodo_pago_seleccionado);
            });
        }

        if ($fechaFiltro){
            $fecha = Carbon::parse($fechaFiltro);
            $query->whereDate('created_at', $fecha);
        }

        $reportes = $query->orderBy('created_at','desc')
                 ->paginate(7)
                 ->appends(request()->query());

        return view('reporte.reportevi',compact('reportes','sedes','fechaFiltro','id_sede','metodos_pagos', 'metodo_pago_seleccionado'));
    }

    public function ventas(Request $request){
        $sedes = Sede::all();
        $user = Auth::user();
        $metodos_pagos = Metodo::all();

        $fechaFiltro = $request->get('fecha_filtro',Carbon::now()->format('Y-m-d'));
        $id_sede = $request->input('id_sede');
        $metodo_pago_seleccionado = $request->input('metodos_pagos');
        $query = DetalleVenta::with([ 'venta','metodo','producto']);

        if ($user->is(User::ROL_ADMIN) && $id_sede) {
            $query->whereHas('venta', function ($q) use ($id_sede) {
                $q->where('fksede', $id_sede);
            });
        }

        if($metodo_pago_seleccionado){
            $query->whereHas('venta', function ($q) use ($metodo_pago_seleccionado) {
                $q->where('fkmetodo', $metodo_pago_seleccionado);
            });
        }

        if ($fechaFiltro){
            $fecha = Carbon::parse($fechaFiltro);
            $query->whereDate('created_at', $fecha);
        }

        $ventasvi = $query->orderBy('created_at','desc')
                 ->paginate(7)
                 ->appends(request()->query());

        return view('reporte.ventavi',compact('ventasvi','sedes','fechaFiltro','id_sede', 'metodos_pagos', 'metodo_pago_seleccionado'));
    }


    public function mostrarFormulario()
    {
        $sedes = Sede::all();
        return view('reporte.reporte', compact('sedes'));
    }

    public function generarReporte(Request $request)
    {
        $request->validate([
            'tipo_reporte' => 'required|in:alumnos,pagos,asistencias,ventas,inventario,pagos_ventas,ingresos_diarios',
            'sede_id' => 'required|integer',
            'fecha' => 'nullable|date',
            'mes' => 'nullable|date_format:Y-m',
        ]);

        $sedeId = $request->sede_id;
        $tipoReporte = $request->tipo_reporte;

        // Manejo de fechas según el tipo de reporte
        switch ($tipoReporte) {
            case 'alumnos':
            case 'inventario':
                // No necesita fechas
                $fechaInicio = null;
                $fechaFin = null;
                break;

            case 'ingresos_diarios':
                // Usa fecha específica (todo el día)
                $fechaInicio = $request->fecha;
                $fechaFin = $request->fecha;
                break;

            case 'pagos':
            case 'ventas':
            case 'pagos_ventas':
            case 'asistencias':
                // Usa mes completo (desde primer día hasta último día del mes)
                if ($request->mes) {
                    $fechaInicio = Carbon::parse($request->mes)->startOfMonth()->toDateString();
                    $fechaFin = Carbon::parse($request->mes)->endOfMonth()->toDateString();
                } else {
                    $fechaInicio = null;
                    $fechaFin = null;
                }
                break;

            default:
                $fechaInicio = null;
                $fechaFin = null;
        }

        switch ($tipoReporte) {
            case 'ingresos_diarios':
                $exportador = new IngresosDiariosPdfExport($fechaInicio, $sedeId);
                return $exportador->generarPdf();

            case 'alumnos':
                return Excel::download(
                    new AlumnosExport($sedeId),
                    'alumnos.xlsx'
                );
            case 'asistencias':
                return Excel::download(
                    new AsistenciasExport($fechaInicio, $fechaFin, $sedeId),
                    'asistencias.xlsx'
                );

            case 'inventario':
                return Excel::download(
                    new InventarioExport(),
                    'inventario.xlsx'
                );

            case 'pagos_ventas':
                return Excel::download(
                    new PagosVentasExport($sedeId, $fechaInicio, $fechaFin),
                    'reporte_combinado_pagos_ventas_'.now()->format('Ymd_His').'.xlsx'
                );

            default:
                return redirect()->back()
                    ->with('error', 'Tipo de reporte no válido.');
        }
    }
}
