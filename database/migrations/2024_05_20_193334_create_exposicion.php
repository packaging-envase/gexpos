<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExposicion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exposicion', function (Blueprint $table) {
            $table->id();

            $table->string('nombre')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('email_expositor')->nullable();
            $table->string('email_administrador_gexpo')->nullable();
            $table->longText('mapa')->nullable();
            $table->string('url_mapa')->nullable();

            $table->boolean('active');

            $table->boolean('modulo_catalogo')->default(false);
            $table->boolean('modulo_plano_stand')->default(false);
            $table->boolean('modulo_elementos_opcionales')->default(false);
            $table->boolean('modulo_deposito_garantia')->default(false);
            $table->boolean('modulo_credenciales')->default(false);
            $table->boolean('modulo_invitaciones')->default(false);
            $table->boolean('modulo_sala_conferencia')->default(false);
            $table->boolean('modulo_solicitar_energia')->default(false);
            $table->boolean('modulo_servicios_optativos')->default(false);

            $table->string('path_terminos_condiciones')->nullable();

            $table->boolean('arhivada')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exposicion');
    }
}
