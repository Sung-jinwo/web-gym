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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id('id_gasto');
            $table->string('gast_descripcion');
            $table->decimal('gast_monto', 8, 2);
            $table->unsignedBigInteger('fkuser'); // Referencia al usuario
            $table->unsignedBigInteger('fksede'); // Referencia a la sede

            $table->foreign('fkuser')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->foreign('fksede')
                ->references('id_sede')
                ->on('sedes')
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
        Schema::dropIfExists('gastos');
    }
};
