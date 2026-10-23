<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpositorModuloCatalogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expositor_modulo_catalogo', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_expositor');

            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('web')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_expositor')->references('id')->on('expositor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expositor_modulo_catalogo');
    }
}
