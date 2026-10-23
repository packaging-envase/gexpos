<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongreso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('congreso', function (Blueprint $table) {
            $table->id();

            $table->string('nombre')->nullable();
            $table->longText('descripcion')->nullable();
            $table->string('location')->nullable();
            $table->date('desde')->nullable();
            $table->date('hasta')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('congreso');
    }
}
