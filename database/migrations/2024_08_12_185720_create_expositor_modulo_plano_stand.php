<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpositorModuloPlanoStand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expositor_modulo_plano_stand', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion')->nullable();
            $table->unsignedBigInteger('id_expositor')->nullable();
            $table->unsignedBigInteger('id_modulo_plano_stand')->nullable();
            
            $table->string('status')->nullalbe();
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_exposicion')->references('id')->on('exposicion');
            $table->foreign('id_expositor')->references('id')->on('expositor');
            $table->foreign('id_modulo_plano_stand')->references('id')->on('modulo_plano_stand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expositor_modulo_plano_stand');
    }
}
