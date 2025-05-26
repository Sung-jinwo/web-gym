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
        Schema::create('padres', function (Blueprint $table) {
            $table->bigIncrements('id_padre');
            $table->string('padre_nombre');
            $table->string('padre_apellido');
            $table->string('padre_telefono', 15);
            $table->string('padre_correo')->nullable();
            $table->unsignedBigInteger('fkalumno');
            $table->timestamps();

            $table->foreign('fkalumno')
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
        Schema::dropIfExists('padres');
    }
};
