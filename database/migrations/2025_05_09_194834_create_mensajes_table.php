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
        Schema::create('mensaje', function (Blueprint $table) {
            $table->id('id_mensaje');
            $table->bigInteger('fkalum')->unsigned();
            $table->string('mensa_problema', '200');
            $table->char('mensa_area', 1);
            $table->char('mensa_periodo', 2);
            $table->string('respuesta_alumno', '200');
            $table->integer('numero_mensaje');
            $table->tinyInteger('mensa_llamar')->nullable();
            $table->char('mensa_cerrar', 1)->nullable();
            $table->timestamps();
            $table->foreign('fkalum')->references('id_alumno')->on('alumno')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mensaje');
    }
};
