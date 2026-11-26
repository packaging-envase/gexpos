<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrensaAcreditacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prensa_acreditacion', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion');

            $table->string('dni');
            $table->string('apellido_nombre');
            $table->string('whatsapp');
            $table->string('medio');
            $table->string('razon_social');

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
        Schema::dropIfExists('prensa_acreditacion');
    }
}
