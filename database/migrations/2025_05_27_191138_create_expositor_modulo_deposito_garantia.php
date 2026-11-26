<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpositorModuloDepositoGarantia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expositor_modulo_deposito_garantia', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion');
            $table->unsignedBigInteger('id_expositor');

            $table->string('apellido')->nullable();
            $table->string('nombre')->nullable();
            $table->string('telefono')->nullable();
            $table->date('fecha_entregado')->nullable();
            $table->boolean('entregado')->default(false);
            $table->string('path')->nullable();
            $table->date('fecha_devolucion')->nullable();

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
        Schema::dropIfExists('expositor_modulo_deposito_garantia');
    }
}
