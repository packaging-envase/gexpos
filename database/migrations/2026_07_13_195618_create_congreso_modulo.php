<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongresoModulo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('congreso_modulo', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_congreso')->nullable();
            $table->unsignedBigInteger('id_locacion')->nullable();

            $table->string('nombre')->nullable();
            $table->longText('descripcion')->nullable();
            $table->longText('auspiciante')->nullable();
            $table->dateTime('desde')->nullable();
            $table->dateTime('hasta')->nullable();

            $table->foreign('id_congreso')->references('id')->on('congreso');
            $table->foreign('id_locacion')->references('id')->on('congreso_modulo_location');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('congreso_modulo');
    }
}
