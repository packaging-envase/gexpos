<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaRepresentada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_representada', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion')->nullable();
            $table->unsignedBigInteger('id_empresa')->nullable();

            $table->string('nombre')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('path_logo')->nullable();

            $table->timestamps();
            $table->softDeletes();            

            $table->foreign('id_exposicion')->references('id')->on('exposicion');
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
        Schema::dropIfExists('empresa_representada');
    }
}
