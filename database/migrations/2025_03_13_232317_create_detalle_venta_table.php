<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->unsignedBigInteger('fkventa');
            $table->unsignedBigInteger('fkproducto');
            $table->integer('datelle_cantidad');
            $table->float('datelle_precio_unitario');
            $table->float('datelle_sub_total');
            $table->foreign('fkventa')->references('id_venta')->on('ventas')->cascadeOnDelete();
            $table->foreign('fkproducto')->references('id_productos')->on('productos')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_venta');
    }
};
