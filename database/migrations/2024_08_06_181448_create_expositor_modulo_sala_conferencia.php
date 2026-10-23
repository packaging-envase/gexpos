<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpositorModuloSalaConferencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expositor_modulo_sala_conferencia', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion')->nullable();
            $table->unsignedBigInteger('id_expositor')->nullable();
            $table->unsignedBigInteger('id_modulo_sala_conferencia')->nullable();
            $table->string('status')->nullable();
            $table->longText('temario')->nullable();
            $table->string('responsable_apellido_nombre')->nullable();
            $table->string('responsable_cargo')->nullable();
            $table->string('responsable_email')->nullable();
            $table->string('responsable_telefono')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_exposicion')->references('id')->on('exposicion');
            $table->foreign('id_expositor')->references('id')->on('expositor');
            $table->foreign('id_modulo_sala_conferencia')->references('id')->on('modulo_sala_conferencia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expositor_modulo_sala_conferencia');
    }
}
