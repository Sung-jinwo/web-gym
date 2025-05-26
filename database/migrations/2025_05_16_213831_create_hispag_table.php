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
        Schema::create('hispag', function (Blueprint $table) {
            $table->id('id_hispag');
            $table->unsignedBigInteger('fkpago')->nullable(false); // Referencia a la membresía
            $table->unsignedBigInteger('fkuser')->nullable(false);
            $table->unsignedBigInteger('fkmem')->nullable(false);
            $table->unsignedBigInteger('fkalum')->nullable(false);
            $table->date('fecha_inicio'); // Fecha del cambio
            $table->date('fecha_fin'); // Fecha del cambio

            $table->timestamps();

            $table->foreign('fkpago')
                ->references('id_pag')
                ->on('pagos')
                ->onUpdate('cascade');

            $table->foreign('fkmem')
                ->references('id_mem')
                ->on('membresias')
                ->onUpdate('cascade');

            $table->foreign('fkuser')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->foreign('fkalum')
                ->references('id_alumno')
                ->on('alumno')
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
        Schema::dropIfExists('hispag');
    }
};
