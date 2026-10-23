<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCredencialDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credencial_detalle', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_expositor')->nullable();
            $table->unsignedBigInteger('id_credencial_tipo')->nullable();

            $table->string('nombre');
            $table->string('apellido');
            $table->string('empresa')->nullable();
            $table->string('cargo')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_expositor')->references('id')->on('expositor');
            $table->foreign('id_credencial_tipo')->references('id')->on('credencial_tipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credencial_detalle');
    }
}
