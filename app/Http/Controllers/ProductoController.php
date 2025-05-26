<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Histoprod;
use App\Models\User;
use App\Models\sede;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateProductoRequest;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
class ProductoController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $sedes = sede::all();

        $user = Auth::user();

        $filtroEstado = $request->get('estado', 'activo');
        $idSede = $request->input('id_sede');
        $query = Producto::with(['categoria', 'user','sede']);

        if ($user->is(User::ROL_ADMIN) && $idSede) {
            $query->where('fksede', $idSede);
        } elseif ($user->is(User::ROL_EMPLEADO)) {
            $query->where('fksede', $user->fksede);
        }


        if ($filtroEstado) {
            $query->where('estado', $filtroEstado);
        }

        // Paginar los resultados
        $producto = $query->latest()->paginate(7)->appends([
            'estado' => $filtroEstado,
            'id_sede' => $idSede,
            ]);

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
            'sedes'=>sede::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductoRequest $request)
    {
        // dd($request->all());

        $producto = new Producto ($request->validated());

        if ($request->hasFile('prod_img')) {
            $imagePath = $request->file('prod_img')->store('images', 'public');
            $producto->prod_img = $imagePath;
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
            'sedes'=>sede::all()

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
            if ($producto->prod_img && Storage::exists($producto->prod_img)) {
                Storage::delete($producto->prod_img);
            }
            $imagePath = $request->file('prod_img')->store('images', 'public');
            $producto->prod_img = $imagePath;

            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::get($producto->prod_img));
            $image->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $encodedImage = $image->encode(new JpegEncoder());
            Storage::put($producto->prod_img, $encodedImage);
            $validatedData['prod_img'] = $imagePath;
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
