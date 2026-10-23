<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloSalaConferencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo_sala_conferencia', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion')->nullable();

            $table->string('sala')->nullable();
            $table->timestamp('fecha')->nullable();
            $table->string('duracion')->nullable();
            $table->string('status')->default('libre');
            $table->longText('temario')->nullable();

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
        Schema::dropIfExists('modulo_sala_conferencia');
    }
}
