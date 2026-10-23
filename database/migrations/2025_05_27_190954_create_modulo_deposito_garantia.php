<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloDepositoGarantia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo_deposito_garantia', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion');
            $table->longText('descripcion');
            $table->date('fecha_cierre');
            $table->string('path')->nullable();

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
        Schema::dropIfExists('modulo_deposito_garantia');
    }
}
