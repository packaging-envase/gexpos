<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpositorModuloPlanoStandFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expositor_modulo_plano_stand_file', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exp_mod_plano_stand')->nullable();
            
            $table->integer('numero_envio')->nullable();
            $table->string('path')->nullable();
            $table->string('name')->nullable();
            $table->boolean('aprobado')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_exp_mod_plano_stand')->references('id')->on('expositor_modulo_plano_stand');
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
