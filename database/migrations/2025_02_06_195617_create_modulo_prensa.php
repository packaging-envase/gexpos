<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloPrensa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo_prensa', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion');

            $table->boolean('aprobado')->default(false);
            $table->string('titulo');
            $table->longText('texto');
            $table->longText('contacto')->nullable();

            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('modulo_prensa');
    }
}
