<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaContacto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_contacto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_empresa')->nullable();

            $table->string('apellido_nombre')->nullable();
            $table->string('cargo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_empresa')->references('id')->on('empresa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa_contacto');
    }
}
