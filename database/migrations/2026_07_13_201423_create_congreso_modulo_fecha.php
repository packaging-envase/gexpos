<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongresoModuloFecha extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('congreso_modulo_fecha', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_congreso_modulo')->nullable();
            $table->unsignedBigInteger('id_congreos_modulo_location')->nullable();
            
            $table->longText('descripcion')->nullable();
            $table->dateTime('desde')->nullable();
            $table->dateTime('hasta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_congreso_modulo')->references('id')->on('congreso_modulo');
            $table->foreign('id_congreos_modulo_location')->references('id')->on('congreso_modulo_location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('congreso_modulo_fecha');
    }
}
