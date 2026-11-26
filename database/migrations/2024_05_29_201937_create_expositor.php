<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpositor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expositor', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion')->nullable();
            $table->unsignedBigInteger('id_empresa')->nullable();

            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('acepto_terminos_condiciones')->nullable();
            $table->boolean('usuario_password_enviado')->nullable();
            $table->string('status')->default('activo');
            $table->boolean('catalogo')->default(false);
            $table->boolean('etif')->default(false);
            $table->boolean('cambio_password')->default(false);
            $table->boolean('aprobado')->default(true);

            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_empresa')->references('id')->on('empresa');
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
        Schema::dropIfExists('expositor');
    }
}
