<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongresoDiplomasRaw extends Migration
{
    public function up()
    {
        Schema::create('congreso_diplomas_raw', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_congreso');
            $table->string('diploma_raw');
            $table->integer('diploma_posision_x')->default(0);
            $table->integer('diploma_posicion_y')->default(0);
            $table->timestamps();

            $table->unique('id_congreso');
        });
    }

    public function down()
    {
        Schema::dropIfExists('congreso_diplomas_raw');
    }
}