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

        $producto = Producto::findOrFail($request->fkproducto);

        // Verificar stock disponible
        if ($producto->prod_cantidad < $request->cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        // Crear la venta
        $venta = Venta::create([
            'fkusers' => $request->fkusers,
            'fkalum' => $request->fkalum,
            'fksede' => $request->fksede,
            'fkmetodo' => $request->fkmetodo,
            'venta_entre' => $request->venta_entre,
            'venta_fecha' => now(),
            'venta_total' => $producto->prod_precio * $request->cantidad
        ]);

        // Crear detalle de venta
        DetalleVenta::create([
            'fkventa' => $venta->id_venta,
            'fkproducto' => $producto->id_productos,
            'fkmetodo' => $venta->fkmetodo,
            'datelle_cantidad' => $request->cantidad,
            'datelle_precio_unitario' => $producto->prod_precio,
            'datelle_sub_total' => $producto->prod_precio * $request->cantidad
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

    public function update(Request $request, Venta $venta)
    {
        // dd($request->all());
        $request->validate([

            'cantidad' => 'required|integer|min:1',
            'fkmetodo' => 'required|exists:metodos_pago,id_metod',
            'fkusers' => 'required|exists:users,id',
            'fkalum' => 'nullable|exists:alumno,id_alumno',
            'fksede' => 'required|exists:sedes,id_sede',
            'venta_entre' => 'nullable|string',
        ]);

        // Obtener el detalle de la venta
        $detalle = $venta->detalles->first(); // Asumimos que hay un solo detalle por venta
        $producto = $detalle->producto;

        // Calcular la diferencia de cantidad (nueva cantidad - cantidad anterior)
        $cantidadAnterior = $detalle->datelle_cantidad;
        $cantidadNueva = $request->cantidad;
        $diferenciaCantidad = $cantidadNueva - $cantidadAnterior;

        // Verificar si hay suficiente stock disponible
        if ($diferenciaCantidad > 0 && $producto->prod_cantidad < $diferenciaCantidad) {
            return back()->with('error', 'No hay suficiente stock disponible para actualizar la venta.');
        }

        // Actualizar el stock del producto
        if ($diferenciaCantidad != 0) {
            $producto->decrement('prod_cantidad', $diferenciaCantidad);
        }

        // Actualizar el detalle de la venta
        $detalle->update([
            'datelle_cantidad' => $cantidadNueva,
            'datelle_precio_unitario' => $producto->prod_precio,
            'datelle_sub_total' => $producto->prod_precio * $cantidadNueva,
        ]);

        // Actualizar la venta
        $venta->update([
            'fkusers' => $request->fkusers,
            'fkalum' => $request->fkalum,
            'fksede' => $request->fksede,
            'fkmetodo' => $request->fkmetodo,
            'venta_total' => $producto->prod_precio * $cantidadNueva,
            'venta_entre' => $request->venta_entre,
        ]);

        // Redireccionar con mensaje de éxito
        return redirect()->route('detalle.index')->with('estado', 'La venta fue actualizada correctamente.');
    }


}
