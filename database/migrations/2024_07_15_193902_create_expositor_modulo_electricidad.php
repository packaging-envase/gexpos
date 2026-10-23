<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpositorModuloElectricidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expositor_modulo_electricidad', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion')->nullable();
            $table->unsignedBigInteger('id_expositor')->nullable();
            $table->number('cantidad')->nullable();
            $table->boolean('tiene_maquinas')->nullable();
            $table->string('peso_medida')->nullable();
            $table->boolean('funcionan')->nullable();
            $table->string('monofasica')->nullable();
            $table->string('trifasica')->nullable();
            $table->string('apellido_nombre')->nullable();
            $table->string('cargo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('email')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_exposicion')->references('id')->on('exposicion');
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
        Schema::dropIfExists('expositor_modulo_elecetricidad');
    }
}
