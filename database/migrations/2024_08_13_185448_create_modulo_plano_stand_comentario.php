<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloPlanoStandComentario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo_plano_stand_comentario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exp_mod_plano_stand')->nullable();
            $table->unsignedBigInteger('id_expositor')->nullable();

            $table->longText('descripcion')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_expositor')->references('id')->on('expositor');
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
        Schema::dropIfExists('modulo_plano_stand_comentario');
    }
}
