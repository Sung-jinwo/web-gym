<?php

use App\Http\Controllers\Admin\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoContoller;
use App\Http\Controllers\MenbresiasContoller;
use App\Http\Controllers\AsistenciaContoller;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\Categori_MController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\DetalleController;
use App\Http\Controllers\RegistroContoller;

use App\Http\Controllers\PadreController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\PagosContoller;
use App\Http\Controllers\GraficaController;
use App\Http\Controllers\GastosController;

Auth::routes(['register' => false]);

Route::view('/', 'home')->name('home')->middleware('auth');

Route::group(['middleware' => ['auth','admin']], function (){
    Route::group(['prefix' => 'users'], function (){
        Route::get('', [UsuarioController::class, 'index'])->name('admin.users.index');
        Route::get('{user}/edit', [UsuarioController::class, 'edit'])->name('admin.users.edit');
        Route::post('{user}/edit', [UsuarioController::class, 'update']);
    });

    Route::group(['prefix' => 'membresias'], function () {
        Route::get('crear',[MenbresiasContoller::class, 'create'])->name('membresias.create');
        Route::get('{membresias}/editar',[MenbresiasContoller::class, 'edit'])->name('membresias.edit');
        Route::patch('{membresias}',[MenbresiasContoller::class, 'update'])->name('membresias.update');

        Route::post('',[MenbresiasContoller::class, 'store'])->name('membresias.store');
        Route::put('{membresias}/estado', [MenbresiasContoller::class, 'cambiarEstado'])->name('membresias.cambiarEstado');
        Route::delete('{membresias}',[MenbresiasContoller::class, 'destroy'])->name('membresias.destroy');
        Route::put('/activar/{membresias}', [MenbresiasContoller::class, 'activar'])->name('membresias.activar');

    });
    // destoy
    Route::get('producto/crear',[ProductoController::class, 'create'])->name('producto.create');

    Route::group(['prefix'=>'graficos'],function (){
        Route::get('',[GraficaController::class, 'index'])->name('graficos.index');
    });
    // ===================================categorias-Membresias============
    Route::group(['prefix' => 'catego_m'], function () {
        Route::get('/crear',[Categori_MController::class, 'create'])->name('catego_m.create');
        Route::get('/{categoria_m}/editar',[Categori_MController::class, 'edit'])->name('catego_m.edit');
        Route::patch('/{categoria_m}',[Categori_MController::class, 'update'])->name('catego_m.update');
        Route::post('',[Categori_MController::class, 'store'])->name('catego_m.store');
        Route::delete('/{categoria_m}',[Categori_MController::class, 'destroy'])->name('catego_m.destroy');
    });


    //================================Categoria de Productosssss-================
    route::group(['prefix' => 'categoria'], function () {
        Route::get('', [CategoriasController::class, 'index'])-> name('categoria.index');
        Route::get('/crear',[CategoriasController::class, 'create'])->name('categoria.create');

        Route::get('/{categoria}/editar',[CategoriasController::class, 'edit'])->name('categoria.edit');
        Route::patch('/{categoria}',[CategoriasController::class, 'update'])->name('categoria.update');
        Route::post('',[CategoriasController::class, 'store'])->name('categoria.store');

        Route::delete('/{categoria}',[CategoriasController::class, 'destroy'])->name('categoria.destroy');
    });



});
//final de admin

Route::group(['middleware' => 'auth'], function (){
    Route::group(['prefix' => 'asistencia'], function (){
        Route::get('', [AsistenciaContoller::class, 'index'])->name('asistencia.index');
        Route::get('crear', [AsistenciaContoller::class, 'create'])->name('asistencia.create');
        Route::post('crear',[AsistenciaContoller::class, 'store'])->name('asistencia.store');

        Route::get('buscar-alumno', [AsistenciaContoller::class, 'buscarAlumno'])->name('asistencia.buscarAlumno');


        Route::get('{asistencia}',[AsistenciaContoller::class, 'show'])->name('asistencia.show');

    });
    //final de Alumno asistencia

    Route::group(['middleware' => 'empleado'], function (){
        
        route::group(['prefix' => 'detalle'], function () {
            Route::get('{detalle}/boleta-pdf',[DetalleController::class, 'boletaPdf'])->name('detalle.boletapdf');
        });
        
        

        Route::group(['prefix' => 'alumno'], function (){
            //el buscar lo usa actualizar ventas
            Route::get('buscar', [AlumnoContoller::class, 'buscarPorCodigo'])->name('alumno.buscar');
            //pruebaaaaa con url

            Route::get('', [AlumnoContoller::class, 'index'])->name('alumno.index');

            Route::get('crear',[AlumnoContoller::class, 'create'])->name('alumno.create');
            Route::get('{alumno}/editar',[AlumnoContoller::class, 'edit'])->name('alumno.edit');

            Route::patch('{alumno}', [AlumnoContoller::class, 'update'])->name('alumno.update');

            Route::put('{alumno}/estado', [AlumnoContoller::class, 'cambiarEstado'])->name('alumno.cambiarEstado');
            Route::post('',[AlumnoContoller::class, 'store'])->name('alumno.store');
            Route::get('{alumno}',[AlumnoContoller::class, 'show'])->name('alumno.show');
            Route::delete('{alumno}',[AlumnoContoller::class, 'destroy'])->name('alumno.destroy');

            Route::get('{alumno}/boleta-pdf',[AlumnoContoller::class, 'boletaPdf'])->name('alumno.boletapdf');
            Route::get('{alumno}/contrato-pdf',[AlumnoContoller::class, 'contratoPdf'])->name('alumno.contratopdf');

        });


//====================================== Prospecto
        Route::group(['prefix'=>'registro'],function (){
            Route::get('',[RegistroContoller::class, 'index'])->name('registro.index');
            Route::get('crear',[RegistroContoller::class, 'create'])->name('registro.create');
            Route::get('{registros}/editar',[RegistroContoller::class, 'edit'])->name('registro.edit');
            Route::get('{registros}/conversar',[RegistroContoller::class, 'conversar'])->name('registro.conversar');
            Route::patch('{registros}/mensaje',[RegistroContoller::class, 'mensaje'])->name('registro.mensaje');
            Route::patch('{registros}', [RegistroContoller::class, 'update'])->name('registro.update');
            Route::put('{registros}/estado', [RegistroContoller::class, 'estado'])->name('registro.estado');
            Route::post('',[RegistroContoller::class, 'store'])->name('registro.store');

            Route::get('{registros}',[RegistroContoller::class, 'show'])->name('registro.show');

            Route::delete('{alumno}',[RegistroContoller::class, 'destroy'])->name('registro.destroy');


        });
///========================Padres
        Route::resource('padres', PadreController::class)->names('padres');
//===============================Gastos=================
        Route::group(['prefix'=>'gasto'],function (){
            Route::get('',[GastosController::class, 'index'])->name('gasto.index');
            Route::get('crear',[GastosController::class, 'create'])->name('gasto.create');
            Route::get('{gastos}/editar',[GastosController::class, 'edit'])->name('gasto.edit');
//            Route::get('{gastos}/conversar',[RegistroContoller::class, 'conversar'])->name('gasto.conversar');
//            Route::patch('{gastos}/mensaje',[RegistroContoller::class, 'mensaje'])->name('registro.mensaje');
            Route::patch('{gastos}', [GastosController::class, 'update'])->name('gasto.update');

//            Route::put('{gastos}/estado', [RegistroContoller::class, 'estado'])->name('registro.estado');

            Route::post('',[GastosController::class, 'store'])->name('gasto.store');

            Route::get('{gastos}',[GastosController::class, 'show'])->name('gasto.show');

            Route::delete('{gastos}',[GastosController::class, 'destroy'])->name('gasto.destroy');

        });

    //======================Asistencias=========================


        // Route::get('asistencia',  [AsistenciaContoller::class,'index']) -> name('asistencia.index');
        // Route::get('asistencia/crear',[AsistenciaContoller::class, 'create'])->name('asistencia.create');
        Route::get('asistencia/{asistencia}/editar',[AsistenciaContoller::class, 'edit'])->name('asistencia.edit');
        Route::patch('asistencia/{asistencia}', [AsistenciaContoller::class, 'update'])->name('asistencia.update');

        // Route::get('asistencia/{asistencia}',[AsistenciaContoller::class, 'show'])->name('asistencia.show');

    //destroy

    //======================Productos=========================
        Route::get('producto', [ProductoController::class, 'index'])->name('producto.index');
        Route::patch('producto/{producto}', [ProductoController::class, 'update'])->name('producto.update');

        Route::post('producto',[ProductoController::class, 'store'])->name('producto.store');
        Route::get('producto/{producto}',[ProductoController::class, 'show'])->name('producto.show');
        Route::delete('producto/{producto}',[ProductoController::class, 'destroy'])->name('producto.destroy');
        Route::put('/producto/activar/{producto}', [ProductoController::class, 'activar'])->name('producto.activar');
    //buscar
    Route::group(['middleware' => 'no-ventas'], function (){
        Route::get('producto/{producto}/editar',[ProductoController::class, 'edit'])->name('producto.edit');
    });


    //==============================Venta======================
        Route::get('venta', [VentaController::class, 'index'])-> name('venta.index');    
        Route::get('venta/reservados', [VentaController::class, 'ventaResarvado'])->name('venta.reservados');
        Route::get('venta/crear/{producto}',[VentaController::class, 'create'])->name('venta.create');
        Route::post('venta',[VentaController::class, 'store'])->name('venta.store');
        Route::get('venta/{venta}',[VentaController::class, 'show'])->name('venta.show');
        Route::get('venta/{venta}/edit', [VentaController::class, 'edit'])->name('venta.edit');
        Route::put('venta/{venta}', [VentaController::class, 'update'])->name('venta.update');
        Route::get('venta/buscar-alumno', [VentaController::class, 'buscarAlumno'])->name('venta.buscarAlumno');

    //======================================Detalle





    //======================Membresias=========================
        Route::group(['prefix' => 'membresias'], function () {
            Route::get('', [MenbresiasContoller::class, 'index'])-> name('membresias.index');

            Route::get('{membresias}',[MenbresiasContoller::class, 'show'])->name('membresias.show');
        });










    //======================Usuarios=========================
    // Route::get('users', [RegisterController::class, 'index'])-> name('users.index');





    // Auth::routes(['register' => false]);

    //======================Pagos=========================
        // Route::get('pagos', [ PagosContoller::class,'index'])->name('pagos.index');
        Route::get('/pagos/completos', [PagosContoller::class, 'pagosCompletos'])->name('pagos.completos');
        Route::get('/pagos/incompletos', [PagosContoller::class, 'pagosIncompletos'])->name('pagos.incompletos');
        Route::get('pagos/crear',[PagosContoller::class, 'create'])->name('pagos.create');
        Route::get('pagos/{pago}/editar',[PagosContoller::class, 'edit'])->name('pagos.edit');
        Route::patch('pagos/{pago}',[PagosContoller::class, 'update'])->name('pagos.update');
        Route::post('pagos',[PagosContoller::class, 'store'])->name('pagos.store');
        Route::get('pagos/{pagos}',[PagosContoller::class, 'show'])->name('pagos.show');
        Route::post('/pagos/congelar/{id}', [PagosContoller::class, 'congelarMembresia'])->name('pagos.congelar');
        Route::post('/pagos/reanudar/{id}', [PagosContoller::class, 'reanudarMembresia'])->name('pagos.reanudar');



    //======================Reporte=========================

        Route::prefix('reportes')->group(function () {
            Route::get('/', [ReporteController::class, 'mostrarFormulario'])->name('reportes.formulario');
            Route::get('/generar-reporte', [ReporteController::class, 'generarReporte'])->name('reporte.generar');
            // Route::get('/exportar-ventas', [ReporteController::class, 'exportarVentas'])->name('exportar.ventas');
    //         Route::get('/inventario', [ReporteController::class, 'exportarInventario'])->name('reporte.inventario');

    // // Reportes rápidos por sede (sin fechas)
    // Route::get('/alumnos-rapido/{sedeId}', [ReporteController::class, 'exportarAlumnosRapido'])->name('reporte.alumnos.rapido');
    // Route::get('/pagos-rapido/{sedeId}', [ReporteController::class, 'exportarPagosRapido'])->name('reporte.pagos.rapido');
    // Route::get('/asistencias-rapido/{sedeId}', [ReporteController::class, 'exportarAsistenciasRapido'])->name('reporte.asistencias.rapido');
    // Route::get('/ventas-rapido/{sedeId}', [ReporteController::class, 'exportarVentasRapido'])->name('reporte.ventas.rapido');

        });


 });

    //======================Alumno=========================
// Route::view('alumvi', 'alumno.alumvi') -> name('alumno');


//===========================Padres=============

});

