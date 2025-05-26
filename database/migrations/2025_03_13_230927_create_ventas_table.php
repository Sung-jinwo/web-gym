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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id('id_venta');
            $table->unsignedBigInteger('fkusers');
            $table->unsignedBigInteger('fkalum')->nullable();
            $table->dateTime('venta_fecha');
            $table->dateTime('venta_total');
            $table->foreign('fkusers')->references('id')->on('users');
            $table->foreign('fkalumno')->references('id_alumno')->on('alumno')->onDelete('set null');
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
        Schema::dropIfExists('ventas');
    }
};
