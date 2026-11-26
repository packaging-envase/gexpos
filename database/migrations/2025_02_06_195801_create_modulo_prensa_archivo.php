<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloPrensaArchivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo_prensa_archivo', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_modulo_prensa');

            $table->string('path');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_modulo_prensa')->references('id')->on('modulo_prensa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modulo_prensa_archivo');
    }
}
