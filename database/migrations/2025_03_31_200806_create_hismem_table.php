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
        Schema::create('hismem', function (Blueprint $table) {
            $table->bigIncrements('id_hismem'); // ID único del registro
            $table->unsignedBigInteger('fkmem')->nullable(false); // Referencia a la membresía
            $table->unsignedBigInteger('fkuser')->nullable(false); // Usuario que realizó el cambio
            $table->decimal('costo_anterior', 8, 2)->nullable(); // Costo antes del cambio
            $table->decimal('costo_nuevo', 8, 2)->nullable(false); // Nuevo costo después del cambio
            $table->date('fecha_edicion'); // Fecha del cambio
            $table->timestamps(); // Fecha de creación y actualización
    
            // Definir claves foráneas
            $table->foreign('fkmem')
                  ->references('id_mem')
                  ->on('membresias')
                  ->onUpdate('cascade');
    
            $table->foreign('fkuser')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hismem');
    }
};
