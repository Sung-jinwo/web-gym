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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_productos');
            $table->foreignId('fkcategoria')->references('id_categoria')->on('categorias')->cascadeOnDelete();
            $table->foreignId('fkusers')->references('id')->on('users')->cascadeOnDelete();
            $table->char('prod_nombre',50);
            $table->string('prod_descripcion',500);
            $table->integer('prod_cantidad');
            $table->float('prod_precio');
            $table->string('prod_img', 255)->nullable();
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
        Schema::dropIfExists('productos');
    }
};
