<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloCatalogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo_catalogo', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion')->nullable();
            
            $table->integer('max_file_size')->default(5);
            $table->integer('max_quantity_productos')->default(10);
            $table->integer('max_caracteres_producto_descripcion')->default(300);
            $table->integer('max_caracteres_empresa')->default(700);
            $table->date('cierre');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_exposicion')->references('id')->on('exposicion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modulo_catalogo');
    }
}
