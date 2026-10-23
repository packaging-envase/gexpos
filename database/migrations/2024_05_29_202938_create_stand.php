<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stand', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_exposicion')->nullable();
            $table->unsignedBigInteger('id_expositor')->nullable();
            $table->string('id_children_mapa')->nullable();

            $table->string('numero')->nullable();
            $table->string('status')->nullable();
            $table->decimal('valor', 12, 2)->nullable();
            $table->integer('moneda');
            $table->string('metros_cuadrados')->nullable();
            $table->string('nombre_exposicion')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_exposicion')->references('id')->on('exposicion');
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
        Schema::dropIfExists('stand');
    }
}
