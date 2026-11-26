<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongresoParticipanteModulo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('congreso_participante_modulo', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_congreso_participante')->nullable();
            $table->unsignedBigInteger('id_congreso_modulo')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_congreso_participante')->references('id')->on('congreso_participante');
            $table->foreign('id_congreso_modulo')->references('id')->on('congreso_modulo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('congreso_participante_modulo');
    }
}
