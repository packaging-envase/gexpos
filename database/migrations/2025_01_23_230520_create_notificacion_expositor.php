<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacionExpositor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacion_expositor', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_notificacion');
            $table->unsignedBigInteger('id_expositor');
            $table->boolean('leido')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_notificacion')->references('id')->on('notificacion');
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
        Schema::dropIfExists('notificacion_expositor');
    }
}
