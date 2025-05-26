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
        Schema::create('pago_detalles', function (Blueprint $table) {
            $table->id('id_pagdeta');
            $table->string('estado', 20)->default('incompleto');
            $table->unsignedBigInteger('fkmetodo');
            $table->unsignedBigInteger('fkpago');
            $table->unsignedBigInteger('fkmemb');

            $table->foreign('fkmetodo')
                  ->references('id_metod')
                  ->on('metodos_pago')
                  ->onUpdate('cascade');

            $table->foreign('fkpago')
                  ->references('id_pag')
                  ->on('pagos')
                  ->onUpdate('cascade');

            $table->foreign('fkmemb')
                  ->references('id_mem')
                  ->on('membresias')
                  ->onUpdate('cascade');
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
        Schema::dropIfExists('pago_detalles');
    }
};
