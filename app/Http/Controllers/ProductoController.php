<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Histoprod;
use App\Models\User;
use App\Models\Sede;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateProductoRequest;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Illuminate\Support\Facades\File;
class ProductoController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $sedes = Sede::all();

        $user = Auth::user();

        $filtroEstado = $request->get('estado', 'activo');
        $idSede = $request->input('id_sede');
        $query = Producto::with(['categoria', 'user','sede']);

        if (($user->is(User::ROL_ADMIN) || $user->is(User::ROL_VENTAS)) && $idSede) {
            $query->where('fksede', $idSede);
        } elseif ($user->is(User::ROL_EMPLEADO)) {
            $query->where('fksede', $user->fksede);
        }


        if ($filtroEstado) {
            $query->where('estado', $filtroEstado);
        }

        // Paginar los resultados
        $producto = $query->orderByDesc('updated_at')
                          ->paginate(8)->appends(request()->query());

        return view('producto.prodvi', compact('producto', 'filtroEstado','sedes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::withRolesAdminAndEmpleado();
        return view('producto.prodcreate',[
            'producto'=> new Producto(),
            'categoria'=> Categoria::all(),
            'users'=> $users,
            'sedes'=> Sede::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductoRequest $request)
    {
        // dd($request->all());

        $producto = new Producto ($request->validated());

        // Manejar la imagen manualmente
        if ($request->hasFile('prod_img')) {
            $image = $request->file('prod_img');
            $imageName = time() . '_' . $image->getClientOriginalName(); // nombre único
//            $destinationPath = public_path('img/productos'); // Ruta absoluta a public/img/images
           $destinationPath = base_path('../public_html/img/productos'); //Para la Web
            // Asegurarse que la carpeta exista
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // crea la carpeta si no existe
            }

            // Mover la imagen a la carpeta deseada
            $image->move($destinationPath, $imageName);

            // Guardar la ruta relativa en la base de datos
            $producto->prod_img = 'img/productos/' . $imageName;
        }

        $producto->save();

        Histoprod::create([
            'fkproducto' => $producto->id_productos, // ID del producto creado
            'fkuser' => auth()->id(), // ID del usuario que realizó la acción
            'fecha_edicion' => now(), // Fecha actual
            'precio_anterior' => null, // No hay precio anterior porque es una creación
            'precio_nuevo' => $producto->prod_precio, // Precio del nuevo producto
            'cantidad_anterior' => null, // No hay cantidad anterior porque es una creación
            'cantidad_nueva' => $producto->prod_cantidad, // Cantidad del nuevo producto
        ]);


        return redirect()
                ->route('producto.index')
                ->with('estado','Se Creo una nuevo Producto');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $producto)
    {
        $producto = Producto::with(['categoria','user','sede'])->find($producto);
        return view('producto.prodshow', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $users = User::withRolesAdminAndEmpleado();
        return view('producto.prodedit',[
            'producto' => $producto,
            'categoria'=>Categoria::all(),
            'users'=>$users,
            'sedes'=> Sede::all()

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Producto $producto,CreateProductoRequest $request)
    {
        $precioAnterior = $producto->prod_precio;
        $cantidadAnterior = $producto->prod_cantidad;
        // prod_img
        $validatedData = $request->validated();
        // dd($request->all());

        if ($request->hasFile('prod_img')) {
            $image = $request->file('prod_img');
            $imageName = time() . '_' . $image->getClientOriginalName();
//            $destinationPath = public_path('img/productos');
            $destinationPath = base_path('../public_html/img/productos'); //Para Produccion


            // Crear la carpeta si no existe
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Eliminar la imagen anterior si existe
//            if ($producto->prod_img && File::exists(public_path($producto->prod_img))) {
//                File::delete(public_path($producto->prod_img));
//            }

            if ($producto->prod_img) {
                $oldImagePath = base_path('../public_html/' . $producto->prod_img);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            } //Condicion para PARA PRODUCCION ELIMINAR


            // Mover la nueva imagen
            $image->move($destinationPath, $imageName);

            // Guardar nueva ruta en datos validados
            $validatedData['prod_img'] = 'img/productos/' . $imageName;
        }



        $producto->update(array_filter($validatedData));

        if ($precioAnterior != $producto->prod_precio || $cantidadAnterior != $producto->prod_cantidad) {
            Histoprod::create([
                'fkproducto' => $producto->id_productos, // ID del producto actualizado
                'fkuser' => auth()->id(), // ID del usuario que realizó la acción
                'fecha_edicion' => now(), // Fecha actual
                'precio_anterior' => $precioAnterior, // Precio antes del cambio
                'precio_nuevo' => $producto->prod_precio, // Nuevo precio después del cambio
                'cantidad_anterior' => $cantidadAnterior, // Cantidad antes del cambio
                'cantidad_nueva' => $producto->prod_cantidad, // Nueva cantidad después del cambio
            ]);
        }

        return redirect()
                ->route('producto.show', $producto)
                ->with('estado', 'El Prodcuto fue actualizado correctamente');

    }

    public function activar(Producto $producto)
    {

        $producto->update(['estado' => 'activo']);

        return redirect()->route('producto.index')
                        ->with('estado', 'El producto fue activado correctamente.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->update(['estado' => 'inactivo']);

        return redirect()->route('producto.index')
                        ->with('info', 'El producto fue inhabilitado correctamente.');
    }
}
