<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpositorModuloElementosOpcionales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expositor_modulo_elementos_opcionales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_modulo_elem_op');
            $table->unsignedBigInteger('id_expositor');
            $table->string('observacion')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_modulo_elem_op')->references('id')->on('modulo_elementos_opcionales');
            $table->foreign('id_expositor')->references('id')->on('expositor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expositor_modulo_elementos_opcionales');
    }
}
