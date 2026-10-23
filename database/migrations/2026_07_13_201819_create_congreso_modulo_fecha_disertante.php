<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongresoModuloFechaDisertante extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('congreso_modulo_fecha_disertante', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_cmf')->nullable();
            $table->unsignedBigInteger('id_cmd')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_cmf')->references('id')->on('congreso_modulo_fecha');
            $table->foreign('id_cmd')->references('id')->on('congreso_modulo_disertante');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('congreso_modulo_fecha_disertante');
    }
}
