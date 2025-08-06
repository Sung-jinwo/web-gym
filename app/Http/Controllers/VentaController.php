<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Metodo;
use App\Models\User;
use App\Models\Sede;
use App\Models\Alumno;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateVentaRequest;

class VentaController extends Controller
{


    public function index(Request $request)
    {
        $sedes = Sede::all();
        $user = Auth::user();

        $idSede = $request->input('id_sede');
        $idProducto = $request->input('id_producto');
        $fechaFiltro = $request->input('fecha_filtro', Carbon::now()->format('Y-m'));
        
        if ($user->is(User::ROL_ADMIN)|| $user->is(User::ROL_VENTAS) ) {
            $productos = Producto::where('estado', 'activo')->orderBy('prod_nombre', 'asc')->get();
        } else {
            $productos = Producto::where('fksede', $user->fksede)
                                 ->where('estado', 'activo')
                                 ->orderBy('prod_nombre', 'asc')
                                 ->get();
        }
        $query = Venta::with(['sede', 'metodo', 'productos']);
        

        // if (($user->is(User::ROL_ADMIN) || $user->is(User::ROL_VENTAS)) && $idSede) {
        //     $query->where('fksede', $idSede);
        // } elseif ($user->is(User::ROL_EMPLEADO)) {
        //     $query->where('fksede', $user->fksede);
        // }

        if ($idProducto) {
            $query->where('fkproducto', $idProducto);
        }

        if ($fechaFiltro) {
            $fecha = Carbon::parse($fechaFiltro);
            $query->whereYear('created_at', $fecha->year)
                  ->whereMonth('created_at', $fecha->month);
        }

    $ventas = $query->where('estado_venta', 'Pagado')
                    ->orderBy('updated_at', 'desc') 
                    ->paginate(7)
                    ->appends(request()->query());

    return view('ventas.ventavi', compact('ventas', 'sedes', 'productos', 'fechaFiltro'));
    }

    public function ventaResarvado(Request $request)
    {
        $sedes = Sede::all();
        $user = Auth::user();

        $idSede = $request->input('id_sede');
        $idProducto = $request->input('id_producto');
        $fechaFiltro = $request->input('fecha_filtro', Carbon::now()->format('Y-m'));
        
        if ($user->is(User::ROL_ADMIN)|| $user->is(User::ROL_VENTAS) ) {
            $productos = Producto::where('estado', 'activo')->orderBy('prod_nombre', 'asc')->get();
        } else {
            $productos = Producto::where('fksede', $user->fksede)
                                 ->where('estado', 'activo')
                                 ->orderBy('prod_nombre', 'asc')
                                 ->get();
        }

        $query = Venta::with(['sede', 'metodo', 'productos']);
        

        // if (($user->is(User::ROL_ADMIN) || $user->is(User::ROL_VENTAS)) && $idSede) {
        //     $query->where('fksede', $idSede);
        // } elseif ($user->is(User::ROL_EMPLEADO)) {
        //     $query->where('fksede', $user->fksede);
        // }

        if ($idProducto) {
            $query->where('fkproducto', $idProducto);
        }

        if ($fechaFiltro) {
            $fecha = Carbon::parse($fechaFiltro);
            $query->whereYear('created_at', $fecha->year)
                  ->whereMonth('created_at', $fecha->month);
        }

    $ventas = $query->where('estado_venta', 'Reservado')
                    ->orderBy('updated_at', 'desc') 
                    ->paginate(7)
                    ->appends(request()->query());


    $ventasCollection = collect($ventas->items());

    // Detectar reservas por vencer
    $reservasPorVencer = $ventasCollection->filter(fn($venta) => $venta->reserva_por_vencer);
    if ($reservasPorVencer->isNotEmpty()) {
        $mensajesWarning = $reservasPorVencer->map(fn($venta) => $venta->mensaje_reserva_por_vencer);
        session()->flash('warning', $mensajesWarning->implode('<br><br>'));
    }
    
    // Detectar reservas vencidas
    $reservasVencidas = $ventasCollection->filter(fn($venta) => $venta->reserva_vencida);
    if ($reservasVencidas->isNotEmpty()) {
        $mensajesError = $reservasVencidas->map(fn($venta) => $venta->mensaje_reserva_vencida);
        session()->flash('error', $mensajesError->implode('<br><br>'));
    }
    return view('ventas.ventare', compact('ventas', 'sedes', 'productos', 'fechaFiltro'));
    }


    public function create(Producto $producto)
    {
        $users = User::withRolesAdminAndEmpleado();
        return view('ventas.vencreate', [
            'producto' => $producto,
            'usuarios' => $users,
            'sedes' => Sede::all(),
            'alumno' => Alumno::all(),
            'metodos' => Metodo::all()
        ]);
    }



    public function store(CreateVentaRequest $request)
    {

        // dd($request->all());
        $validatedData = $request->validated();

        $producto = Producto::findOrFail($request->fkproducto);

        // Verificar stock disponible
        if ($producto->prod_cantidad < $request->cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }
        
        $incrementado= $validatedData['venta_incrementado'];

        $subtotalNuevo = $producto->prod_precio * $request->cantidad;
        $venta_total = $subtotalNuevo + $incrementado;
        $pago_venta = $subtotalNuevo ;
        

        // $total= $producto->prod_precio * $request->cantidad;
        $monto = $validatedData['estado_venta'] === 'Pagado' ? $venta_total : $validatedData['venta_pago'];
        $pago = $validatedData['estado_venta'] === 'Pagado' ? $pago_venta : $validatedData['venta_pago'];

        $pendiente =  ($venta_total -  $monto) ;


        $saldoPendiente = $validatedData['estado_venta'] === 'Reservado' ? $pendiente : 0;

        // Crear la venta
        $venta = Venta::create([
            'fkusers' => $request->fkusers,
            'fkalum' => $request->fkalum,
            'fksede' => $request->fksede,
            'fkmetodo' => $request->fkmetodo,
            'estado_venta' => $request->estado_venta,
            'venta_entre' => $request->venta_entre,
            'venta_fecha' => $request->venta_fecha,
            'cantidad' => $request->cantidad,
            'venta_pago'=> $pago,
            'fkproducto' => $producto->id_productos,
            'venta_saldo' => $saldoPendiente,
            'venta_incrementado' => $incrementado,
            'venta_total' => $monto ,
        ]);
        $metodo_2 = $request->input('fkmetodo_2');
        $monto_2 = $request->input('monto_2');
        
        // Crear detalle de venta
        if($metodo_2 && $monto_2 > 0) {
            $monto_1 = $monto - $monto_2;

                // Primer detalle
            DetalleVenta::create([
                'fkventa' => $venta->id_venta,
                'fkproducto' => $producto->id_productos,
                'fkmetodo' => $venta->fkmetodo,
                'estado_venta' => $request->estado_venta,
                'datelle_cantidad' => $request->cantidad,
                'datelle_precio_unitario' => $producto->prod_precio,
                'datelle_sub_total' => $monto_1,
            ]);
            

            DetalleVenta::create([
                'fkventa' => $venta->id_venta,
                'fkproducto' => $producto->id_productos,
                'fkmetodo' => $metodo_2,
                'estado_venta' => $request->estado_venta,
                'datelle_cantidad' => 0,
                'datelle_precio_unitario' => 0,
                'datelle_sub_total' => $monto_2,
            ]);
        }else {
            DetalleVenta::create([
                'fkventa' => $venta->id_venta,
                'fkproducto' => $producto->id_productos,
                'fkmetodo' => $venta->fkmetodo,
                'estado_venta' => $request->estado_venta,
                'datelle_cantidad' => $request->cantidad,
                'datelle_precio_unitario' => $producto->prod_precio,
                'datelle_sub_total' => $monto ,
            ]);
        }
        
        if (($monto_2 > 0) && ($monto_2 > $monto)) {
            return back()->with('error', 'El segundo monto no puede ser mayor al total ingresado.');
        }

        // Actualizar stock del producto
        $producto->decrement('prod_cantidad', $request->cantidad);

        return redirect()->route('producto.index')
            ->with('estado', 'Venta realizada con éxito');
    }

    public function show($venta)
    {
        $venta = Venta::with(['productos'])->find($venta);
        return view('ventas.ventashow', compact('venta'));
    }
    public function edit(Venta $venta)
    {
        // Cargar relaciones necesarias para el formulario
        $venta->load('detalles.producto'); // Cargar los detalles de la venta y sus productos asociados

        // Obtener datos adicionales para el formulario
        $productos = Producto::all();
        $metodos = Metodo::all();
        $sedes = Sede::all();
        $usuarios = User::withRolesAdminAndEmpleado();;

        $alumno = Alumno::where('alum_codigo', $venta->fkalum)->first();

        return view('ventas.venedit',
            compact('venta', 'productos', 'metodos', 'sedes', 'usuarios'));
    }

    public function update(CreateVentaRequest $request, Venta $venta)
    {
        $validatedData = $request->validated();
    
        $detalle = $venta->detalles()
            ->where('datelle_cantidad', '>', 0)
            ->orderBy('id_detalle', 'asc')
            ->first();
    
        if (!$detalle) {
            return back()->with('error', 'No se encontró un detalle válido.');
        }
    
        $producto = $detalle->producto;
    
        $cantidadAnterior = $detalle->datelle_cantidad;
        $cantidadNueva = $request->cantidad;
        $diferenciaCantidad = $cantidadNueva - $cantidadAnterior;
        
        $incrementado = $validatedData['venta_incrementado'];
        $precioUnitario = $producto->prod_precio;

        $subtotalNuevo = $producto->prod_precio * $cantidadNueva;
        $venta_total = $subtotalNuevo + $incrementado;
        $pago_venta = $subtotalNuevo ;

    
    
        $estadoventa = $validatedData['estado_venta'];
        $monto = $estadoventa === 'Pagado' ? $venta_total : $validatedData['venta_pago'];
        $pago = $validatedData['estado_venta'] === 'Pagado' ? $pago_venta : $validatedData['venta_pago'];
    
        $estadoReserva = $estadoventa === 'Reservado';
        $pendiente =  ($venta_total -  $monto) ;
        $saldoPendiente = $estadoReserva ? $pendiente : 0;

        $incrementadoAnterior = $venta->venta_incrementado;
        $debeActualizarDetalle = false;

        if ($diferenciaCantidad !== 0) {
            if ($diferenciaCantidad > 0 && $producto->prod_cantidad < $diferenciaCantidad) {
                return back()->with('error', 'No hay suficiente stock disponible para actualizar la venta.');
            }

            $producto->decrement('prod_cantidad', $diferenciaCantidad);
            $debeActualizarDetalle = true;
        }

        if ($incrementado != $incrementadoAnterior) {
            $debeActualizarDetalle = true;
        }

        if ($debeActualizarDetalle) {
            $detalle->update([
                'datelle_cantidad' => $cantidadNueva,
                'datelle_precio_unitario' => $precioUnitario,
                'datelle_sub_total' => $monto,
            ]);
        }

        if ($venta->fkmetodo != $request->fkmetodo) {
            $venta->detalles()->update([
                'fkmetodo' => $request->fkmetodo
            ]);
        }
    
    
        $venta->update([
            'fkusers' => $request->fkusers,
            'fkalum' => $request->fkalum,
            'fksede' => $request->fksede,
            'cantidad' => $cantidadNueva,
            'fkmetodo' => $request->fkmetodo,
            'estado_venta' => $estadoventa,
            'venta_fecha' => $request->venta_fecha,
            'venta_pago' => $pago,
            'venta_incrementado' => $incrementado,
            'venta_total' => $monto ,
            'venta_entre' => $request->venta_entre,
            'venta_saldo' => $saldoPendiente,
        ]);

        $totalPagadoAntes = $venta->detalles()->sum('datelle_sub_total');

    
        // Saldo restante a registrar como nuevo detalle
        $saldo = $monto - $totalPagadoAntes;
    
        if ($saldo > 0) {
            DetalleVenta::create([
                'fkventa' => $venta->id_venta,
                'fkproducto' => $producto->id_productos,
                'fkmetodo' => $venta->fkmetodo,
                'estado_venta' => $estadoventa,
                'datelle_cantidad' => 0,
                'datelle_precio_unitario' => 0,
                'datelle_sub_total' => $saldo,
            ]);
        }
    
        $redirect = redirect();

        if ($estadoventa === 'Reservado') {
            return $redirect->route('venta.reservados')
            ->with('estado', 'La venta fue actualizado correctamente.');
        }else{
            return $redirect->route('venta.index')
            ->with('estado', 'La venta pago fue actualizado correctamente.');
        }
    }
    


}
