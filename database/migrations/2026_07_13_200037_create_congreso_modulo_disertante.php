<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongresoModuloDisertante extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('congreso_modulo_disertante', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_congreso_modulo');

            $table->string('apellido_nombre')->nullable();
            $table->string('titulo')->nullable();
            $table->string('especialidad')->nullable();
            $table->string('cargo')->nullable();
            $table->string('empresa')->nullable();
            $table->string('path_foto')->nullable();
            $table->longText('descripcion')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_congreso_modulo')->references('id')->on('congreso_modulo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('congreso_modulo_disertante');
    }
}
