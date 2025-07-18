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
use App\Http\Requests\CreateVentaRequest;

class VentaController extends Controller
{





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

        $subtotalNuevo = $producto->prod_precio * $request->cantidad;
        $venta_total = $subtotalNuevo;

        $total= $producto->prod_precio * $request->cantidad;
        $monto = $validatedData['estado_venta'] === 'Pagado' ? $venta_total : $validatedData['venta_pago'];

        // Crear la venta
        $venta = Venta::create([
            'fkusers' => $request->fkusers,
            'fkalum' => $request->fkalum,
            'fksede' => $request->fksede,
            'fkmetodo' => $request->fkmetodo,
            'estado_venta' => $request->estado_venta,
            'venta_entre' => $request->venta_entre,
            'venta_fecha' => $request->venta_fecha,
            'venta_pago'=> $request->venta_pago,
            'venta_saldo' => $total -$request->venta_pago,
            'venta_total' => $total
        ]);

        // Crear detalle de venta
        DetalleVenta::create([
            'fkventa' => $venta->id_venta,
            'fkproducto' => $producto->id_productos,
            'fkmetodo' => $venta->fkmetodo,
            'estado_venta' => $request->estado_venta,
            'datelle_cantidad' => $request->cantidad,
            'datelle_precio_unitario' => $producto->prod_precio,
            'datelle_sub_total' => $monto,
        ]);

        // Actualizar stock del producto
        $producto->decrement('prod_cantidad', $request->cantidad);

        return redirect()->route('producto.index')
            ->with('estado', 'Venta realizada con éxito');
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
    
        $subtotalNuevo = $producto->prod_precio * $cantidadNueva;
        $venta_total = $subtotalNuevo;
    
        // 👉 Calcular total pagado ANTES de modificar el detalle
        $totalPagadoAntes = $venta->detalles()->sum('datelle_sub_total');
    
        $estadoventa = $validatedData['estado_venta'];
        $monto = $estadoventa === 'Pagado' ? $venta_total : $validatedData['venta_pago'];
    
        $estadoReserva = $estadoventa === 'Reservado';
        $saldoPendiente = $estadoReserva ? $validatedData['venta_saldo'] : 0;
        if ($diferenciaCantidad !== 0) {
    
            if ($diferenciaCantidad > 0 && $producto->prod_cantidad < $diferenciaCantidad) {
                return back()->with('error', 'No hay suficiente stock disponible para actualizar la venta.');
            }
    
            // Actualizar stock
            $producto->decrement('prod_cantidad', $diferenciaCantidad);
    
            // Actualizar detalle
            $detalle->update([
                'datelle_cantidad' => $cantidadNueva,
                'datelle_precio_unitario' => $producto->prod_precio,
                'datelle_sub_total' => $subtotalNuevo,
            ]);
        }
    
        // Actualizar la venta
        $venta->update([
            'fkusers' => $request->fkusers,
            'fkalum' => $request->fkalum,
            'fksede' => $request->fksede,
            'fkmetodo' => $request->fkmetodo,
            'estado_venta' => $estadoventa,
            'venta_fecha' => $request->venta_fecha,
            'venta_pago' => $monto,
            'venta_total' => $venta_total,
            'venta_entre' => $request->venta_entre,
            'venta_saldo' => $saldoPendiente,
        ]);
    
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
    
        return redirect()->route('detalle.index')->with('estado', 'La venta fue actualizada correctamente.');
    }
    


}
