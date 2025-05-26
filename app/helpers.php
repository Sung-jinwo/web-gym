<?php

use App\Models\Ctegoria_m;
use App\Models\Membresias;

    function setActivo($ruta){

        //para marcar rutas sin agrupar     ||  para rutas agrupadas
        return request()->routeIs($ruta . '*') || request()->is($ruta . '/*') ? 'activo' : '';
    }
// En tu helpers.php o AppServiceProvider.php

    function getRutina()
    {
        $rutinaCat = Ctegoria_m::where('nombre_m', 'Rutina')->first('id_categoria_m');

        return Membresias::where('fkcategoria', $rutinaCat->id_categoria_m)->first('id_mem');
    }
?>
