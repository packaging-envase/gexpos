<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailPredefinidoToExpositor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_predefinido_to_expositor', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_email_predefinido');
            $table->unsignedBigInteger('id_expositor');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_email_predefinido', 'fk_email_predefinido_id')->references('id')->on('email_predefinido');
            $table->foreign('id_expositor', 'fk_expositor_id')->references('id')->on('expositor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_predefinido_to_expositor');
    }
}
