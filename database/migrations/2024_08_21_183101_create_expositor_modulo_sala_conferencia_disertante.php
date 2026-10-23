<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpositorModuloSalaConferenciaDisertante extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expositor_modulo_sala_conferencia_disertante', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_expositor_modulo_sala_conferencia')->nullable();

            $table->string('apellido_nombre')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_expositor_modulo_sala_conferencia', 'fk_sala_conf_id')->references('id')->on('expositor_modulo_sala_conferencia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expositor_modulo_sala_conferencia_disertante');
    }
}
