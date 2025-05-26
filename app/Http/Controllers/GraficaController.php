<?php

namespace App\Http\Controllers;

use App\Models\PagoDetalle;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraficaController extends Controller
{
    public function index(Request $request)
    {
        $fechaFiltro = $request->get('fecha_filtro', date('Y'));

        if (!empty($fechaFiltro) && !preg_match('/^\d{4}$/', $fechaFiltro)) {
            $fechaFiltro = date('Y');
        }

        //filtrado por año

        $mesesBase = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        ];

        $applyYearFilter = function($query) use ($fechaFiltro) {
            if (!empty($fechaFiltro)) {
                $query->whereYear('created_at', $fechaFiltro);
            }
        };

        // Función para aplicar filtro por año en consultas con join
        $applyYearFilterWithTable = function($table) use ($fechaFiltro) {
            return function($query) use ($fechaFiltro, $table) {
                if (!empty($fechaFiltro)) {
                    $query->whereYear($table . '.created_at', $fechaFiltro);
                }
            };
        };


        // Consultas básicas (sin joins)
        $pagos = DB::table('pago_detalles')
            ->when($fechaFiltro, $applyYearFilter)
            ->selectRaw('MONTH(created_at) as mes_num, MONTHNAME(created_at) as mes, SUM(monto) as total')
            ->groupBy('mes_num', 'mes')
            ->orderBy('mes_num')
            ->get()
            ->keyBy('mes');

        $ventas = DB::table('ventas')
            ->when($fechaFiltro, $applyYearFilter)
            ->selectRaw('MONTH(created_at) as mes_num, MONTHNAME(created_at) as mes, SUM(venta_total) as total')
            ->groupBy('mes_num', 'mes')
            ->orderBy('mes_num')
            ->get()
            ->keyBy('mes');

        $ventasMembresias = DB::table('pagos')
            ->when($fechaFiltro, $applyYearFilter)
            ->selectRaw('MONTH(created_at) as mes_num, MONTHNAME(created_at) as mes_nombre, fkmem, COUNT(*) as cantidad')
            ->groupBy('mes_num', 'mes_nombre', 'fkmem')
            ->orderBy('mes_num')
            ->get();

        $membresias = DB::table('membresias')->pluck('mem_nomb', 'id_mem');

        // Consultas con joins (usando $applyYearFilterWithTable)
        $alumnosPorMesSede = DB::table('alumno as a')
            ->join('sedes as s', 's.id_sede', '=', 'a.fksede')
            ->when($fechaFiltro, $applyYearFilterWithTable('a'))
            ->selectRaw('MONTH(a.created_at) as mes_num, MONTHNAME(a.created_at) as mes, s.sede_nombre, COUNT(*) as total')
            ->whereNotNull('a.alum_codigo')
            ->groupBy('s.id_sede', 'mes_num', 'mes', 's.sede_nombre')
            ->orderBy('mes_num')
            ->orderBy('s.sede_nombre')
            ->get();

        $renovacionesPorMesSede = DB::table('hispag as h')
            ->join('sedes as s', 's.id_sede', '=', 'h.fksede')
            ->when($fechaFiltro, $applyYearFilterWithTable('h'))
            ->selectRaw('s.sede_nombre, MONTHNAME(h.created_at) as mes, MONTH(h.created_at) as mes_num, COUNT(DISTINCT h.fkalum) as total')
            ->groupBy('s.id_sede', 'mes_num', 'mes', 's.sede_nombre')
            ->orderBy('mes_num')
            ->orderBy('s.sede_nombre')
            ->get();

        // Ventas entrenadores (corregido)
        $ventasPorEntrenador = DB::table('ventas as v')
            ->join('sedes as s', 's.id_sede', '=', 'v.fksede')
            ->when($fechaFiltro, $applyYearFilterWithTable('v'))
            ->selectRaw('
            s.sede_nombre,
            v.venta_entre,
            MONTHNAME(v.created_at) as mes,
            MONTH(v.created_at) as mes_num,
            COUNT(*) as total
        ')
            ->whereNotNull('v.venta_entre')
            ->groupBy('s.id_sede', 'v.venta_entre', 'mes_num', 'mes', 's.sede_nombre')
            ->orderBy('mes_num')
            ->orderBy('s.sede_nombre')
            ->orderBy('v.venta_entre')
            ->get();

        // Pagos entrenadores (corregido)
        $pagosPorEntrenador = DB::table('pagos as p')
            ->join('sedes as s', 's.id_sede', '=', 'p.fksede')
            ->when($fechaFiltro, $applyYearFilterWithTable('p'))
            ->selectRaw('
            s.sede_nombre,
            p.pag_entre,
            MONTHNAME(p.created_at) as mes,
            MONTH(p.created_at) as mes_num,
            COUNT(*) as total
        ')
            ->whereNotNull('p.pag_entre')
            ->groupBy('s.id_sede', 'p.pag_entre', 'mes_num', 'mes', 's.sede_nombre')
            ->orderBy('mes_num')
            ->orderBy('s.sede_nombre')
            ->orderBy('p.pag_entre')
            ->get();

        // Productos vendidos (corregido)
        $productosVendidos = DB::table('detalle_venta as dv')
            ->join('ventas as v', 'v.id_venta', '=', 'dv.fkventa')
            ->join('productos as p', 'p.id_productos', '=', 'dv.fkproducto')
            ->join('sedes as s', 's.id_sede', '=', 'v.fksede')
            ->when($fechaFiltro, $applyYearFilterWithTable('v'))
            ->selectRaw('
            s.sede_nombre,
            MONTHNAME(v.created_at) as mes,
            MONTH(v.created_at) as mes_num,
            p.prod_nombre,
            SUM(dv.datelle_cantidad) as cantidad_vendida
        ')
            ->groupBy('s.id_sede', 'mes_num', 'mes', 's.sede_nombre', 'p.prod_nombre')
            ->orderBy('mes_num')
            ->orderBy('s.sede_nombre')
            ->orderBy('p.prod_nombre')
            ->get();

        // Gastos por sede (corregido)
        $gastosPorMesSede = DB::table('gastos as g')
            ->join('sedes as s', 's.id_sede', '=', 'g.fksede')
            ->when($fechaFiltro, $applyYearFilterWithTable('g'))
            ->selectRaw('
            s.sede_nombre,
            MONTHNAME(g.created_at) as mes,
            MONTH(g.created_at) as mes_num,
            SUM(g.gast_monto) as total
        ')
            ->groupBy('s.id_sede', 'mes_num', 'mes', 's.sede_nombre')
            ->orderBy('mes_num')
            ->orderBy('s.sede_nombre')
            ->get();





// Consulta para métodos de pago utilizados por mes
        // Consulta para obtener la cantidad total de transacciones por método de pago y mes
        $metodosPagoPorMes = DB::table('metodos_pago as mp')
            ->leftJoin(DB::raw('(SELECT
        fkmetodo,
        MONTH(created_at) as mes_num,
        MONTHNAME(created_at) as mes,
        COUNT(*) as cantidad,
        SUM(datelle_sub_total) as total_ventas
        FROM detalle_venta
        WHERE ' . ($fechaFiltro ? 'YEAR(created_at) = ' . $fechaFiltro : '1=1') . '
        GROUP BY fkmetodo, mes_num, mes) as ventas'),
                function($join) {
                    $join->on('mp.id_metod', '=', 'ventas.fkmetodo');
                })
            ->leftJoin(DB::raw('(SELECT
        fkmetodo,
        MONTH(created_at) as mes_num,
        MONTHNAME(created_at) as mes,
        COUNT(*) as cantidad,
        SUM(monto) as total_pagos
        FROM pago_detalles
        WHERE ' . ($fechaFiltro ? 'YEAR(created_at) = ' . $fechaFiltro : '1=1') . '
        GROUP BY fkmetodo, mes_num, mes) as pagos'),
                function($join) {
                    $join->on('mp.id_metod', '=', 'pagos.fkmetodo');
                })
            ->selectRaw('
        mp.id_metod,
        mp.tipo_pago,
        COALESCE(ventas.mes_num, pagos.mes_num) as mes_num,
        COALESCE(ventas.mes, pagos.mes) as mes,
        COALESCE(ventas.cantidad, 0) as cantidad_ventas,
        COALESCE(pagos.cantidad, 0) as cantidad_pagos,
        (COALESCE(ventas.cantidad, 0) + COALESCE(pagos.cantidad, 0)) as cantidad_total,
        COALESCE(ventas.total_ventas, 0) as total_ventas,
        COALESCE(pagos.total_pagos, 0) as total_pagos,
        (COALESCE(ventas.total_ventas, 0) + COALESCE(pagos.total_pagos, 0)) as monto_total
    ')
            ->where(function($query) {
                $query->whereNotNull('ventas.mes_num')
                    ->orWhereNotNull('pagos.mes_num');
            })
            ->orderBy('mes_num')
            ->orderBy('mp.tipo_pago')
            ->get();

        $todosLosMeses = collect($ventasMembresias->pluck('mes_nombre'))
            ->merge($ventas->keys())
            ->merge($pagos->keys())
            ->merge($alumnosPorMesSede->pluck('mes'))
            ->merge($renovacionesPorMesSede->pluck('mes'))
            ->merge($pagosPorEntrenador->pluck('mes'))
            ->merge($gastosPorMesSede->pluck('mes'))
            ->merge($ventasPorEntrenador->pluck('mes'))
            ->merge($productosVendidos->pluck('mes'))
            ->merge($metodosPagoPorMes->pluck('mes'))
            ->filter()
            ->unique()
            ->sortBy(function($mes) {
                return \DateTime::createFromFormat('F', $mes)->format('n'); // Ordena por número de mes (1-12)
            })
            ->values();



        $labels = $todosLosMeses->map(fn($mes) => $mesesBase[$mes])->toArray();

        $dataPagos = [];
        $dataVentas = [];
        $dataGananciaNeta = [];

        foreach ($todosLosMeses as $mes) {
            $ingresosPagos = $pagos[$mes]->total ?? 0;
            $ingresosVentas = $ventas[$mes]->total ?? 0;

            $gastoMes = $gastosPorMesSede->where('mes', $mes)->sum('total') ?? 0;

            $dataPagos[] = $ingresosPagos;
            $dataVentas[] = $ingresosVentas;
            $dataGananciaNeta[] = $ingresosPagos + $ingresosVentas - $gastoMes;
        }


        $dataMembresias = [];

        foreach ($membresias as $id => $nombre) {
            $serie = [];

            foreach ($todosLosMeses as $mes) {
                $registro = $ventasMembresias->first(fn($v) => $v->mes_nombre === $mes && $v->fkmem == $id);
                $serie[] = $registro ? $registro->cantidad : 0;
            }

            $dataMembresias[] = [
                'label' => $nombre,
                'data' => $serie
            ];
        }

        $dataMembresiasChart = collect($dataMembresias)->map(function ($m, $i) {
            $colors = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba'];
            return array_merge($m, [
                'fill' => false,
                'borderColor' => $colors[$i % count($colors)],
                'backgroundColor' => $colors[$i % count($colors)],
                'tension' => 0.1
            ]);
        })->values();

        $sedesAgrupadas = $alumnosPorMesSede->groupBy('sede_nombre');
        $dataAlumnosPorSede = [];
        foreach ($sedesAgrupadas as $sede => $datos) {
            $datosPorMes = $datos->keyBy('mes');
            $serie = [];
            foreach ($todosLosMeses as $mes) {
                $serie[] = $datosPorMes[$mes]->total ?? 0;
            }
            $dataAlumnosPorSede[] = [
                'label' => $sede,
                'data' => $serie,
                'fill' => false,
                'borderColor' => '#'.substr(md5($sede), 0, 6), // color distinto por sede
                'tension' => 0.1
            ];
        }

        $renovacionesAgrupadas = $renovacionesPorMesSede->groupBy('sede_nombre');
        $dataRenovacionesPorSede = [];

        foreach ($renovacionesAgrupadas as $sede => $datos) {
            $datosPorMes = $datos->keyBy('mes');
            $serie = [];
            foreach ($todosLosMeses as $mes) {
                $serie[] = $datosPorMes[$mes]->total ?? 0;
            }
            $dataRenovacionesPorSede[] = [
                'label' => $sede,
                'data' => $serie,
                'fill' => false,
                'borderColor' => '#'.substr(md5('renova_'.$sede), 0, 6), // color diferente
                'tension' => 0.1
            ];
        }

        $entrenadoresAgrupados = $pagosPorEntrenador->groupBy(function($item) {
            return $item->sede_nombre . '|' . $item->pag_entre; // clave compuesta
        });
        $dataEntrenadoresPorSede = [];
        foreach ($entrenadoresAgrupados as $clave => $registros) {
            [$sede, $tipoTrainer] = explode('|', $clave);
            $datosPorMes = collect($registros)->keyBy('mes');
            $serie = [];

            foreach ($todosLosMeses as $mes) {
                $serie[] = $datosPorMes[$mes]->total ?? 0;
            }

            $dataEntrenadoresPorSede[] = [
                'label' => "$sede - $tipoTrainer",
                'data' => $serie,
                'fill' => false,
                'borderColor' => '#'.substr(md5($clave), 0, 6),
                'backgroundColor' => '#'.substr(md5($clave), 0, 6),
                'tension' => 0.1
            ];
        }

        $ventasentrenadoresAgrupados = $ventasPorEntrenador->groupBy(function($item) {
            return $item->sede_nombre . '|' . $item->venta_entre; // clave compuesta
        });

        $dataEntrenadoresvPorSede = [];
        foreach ($ventasentrenadoresAgrupados as $clave => $registros) {
            [$sede, $tipoTrainer] = explode('|', $clave);
            $datosPorMes = collect($registros)->keyBy('mes');
            $serie = [];

            foreach ($todosLosMeses as $mes) {
                $serie[] = $datosPorMes[$mes]->total ?? 0;
            }

            $dataEntrenadoresvPorSede[] = [
                'label' => "$sede - $tipoTrainer",
                'data' => $serie,
                'fill' => false,
                'borderColor' => '#'.substr(md5($clave), 0, 6),
                'backgroundColor' => '#'.substr(md5($clave), 0, 6),
                'tension' => 0.1
            ];
        }


        $dataProductosVendidosPorSede = [];
        $productosAgrupados = $productosVendidos->groupBy(function($item) {
            return $item->sede_nombre . '|' . $item->prod_nombre;
        });

        foreach ($productosAgrupados as $clave => $items) {
            [$sede, $producto] = explode('|', $clave);
            $datosPorMes = $items->keyBy('mes');
            $serie = [];

            foreach ($todosLosMeses as $mes) {
                $serie[] = $datosPorMes[$mes]->cantidad_vendida ?? 0;
            }

            $dataProductosVendidosPorSede[] = [
                'label' => "$sede - $producto",
                'data' => $serie,
                'fill' => false,
                'borderColor' => '#' . substr(md5($clave), 0, 6),
                'backgroundColor' => '#' . substr(md5($clave), 0, 6),
                'tension' => 0.1
            ];
        }

        $gastosAgrupados = $gastosPorMesSede->groupBy('sede_nombre');
        $dataGastosPorSede = [];

        foreach ($gastosAgrupados as $sede => $datos) {
            $datosPorMes = $datos->keyBy('mes');
            $serie = [];

            foreach ($todosLosMeses as $mes) {
                $serie[] = $datosPorMes[$mes]->total ?? 0;
            }

            $dataGastosPorSede[] = [
                'label' => "$sede - Gastos",
                'data' => $serie,
                'fill' => false,
                'borderColor' => '#' . substr(md5("gasto_" . $sede), 0, 6),
                'backgroundColor' => '#' . substr(md5("gasto_" . $sede), 0, 6),
                'tension' => 0.1
            ];
        }

        $dataMetodosPago = [];
        $metodosAgrupados = $metodosPagoPorMes->groupBy('tipo_pago');

        foreach ($metodosAgrupados as $metodo => $registros) {
            $datosPorMes = $registros->keyBy('mes');
            $serie = [];

            foreach ($todosLosMeses as $mes) {
                $serie[] = $datosPorMes[$mes]->cantidad_total ?? 0;
            }

            $dataMetodosPago[] = [
                'label' => $metodo,
                'data' => $serie,
                'fill' => false,
                'borderColor' => '#'.substr(md5($metodo), 0, 6),
                'backgroundColor' => '#'.substr(md5($metodo), 0, 6),
                'tension' => 0.1
            ];
        }

        return view('graficos.graficvi', compact('labels',
             'dataPagos',
                        'dataVentas',
                        'dataMembresias',
                        'dataMembresiasChart',
                        'dataAlumnosPorSede',
                        'dataRenovacionesPorSede',
                        'dataEntrenadoresPorSede',
                        'dataEntrenadoresvPorSede',
                        'dataProductosVendidosPorSede',
                        'dataGastosPorSede',
                        'dataGananciaNeta',
                    'fechaFiltro',
                    'dataMetodosPago'
        ));
    }
}
