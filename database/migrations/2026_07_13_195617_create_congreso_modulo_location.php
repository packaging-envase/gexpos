<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongresoModuloLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('congreso_modulo_location', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_congreso')->nullable();

            $table->string('descripcion')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_congreso')->references('id')->on('congreso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('congreso_modulo_location');
    }
}
