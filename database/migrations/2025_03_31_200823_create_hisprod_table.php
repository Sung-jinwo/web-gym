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
        Schema::create('hisprod', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('fkproducto')->nullable(false); // Referencia al producto
            $table->unsignedBigInteger('fkuser')->nullable(false); // Usuario que realizó el cambio
            $table->decimal('precio_anterior', 8, 2)->nullable(); // Precio antes del cambio
            $table->decimal('precio_nuevo', 8, 2)->nullable(false); // Nuevo precio después del cambio
            $table->integer('cantidad_anterior')->nullable(); // Cantidad antes del cambio
            $table->integer('cantidad_nueva')->nullable(false); // Nueva cantidad después del cambio
            $table->date('fecha_edicion'); // Fecha del cambio
            $table->timestamps(); // Fecha de creación y actualización
    
            // Definir claves foráneas
            $table->foreign('fkproducto')
                  ->references('id_productos')
                  ->on('productos')
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
        Schema::dropIfExists('hisprod');
    }
};
