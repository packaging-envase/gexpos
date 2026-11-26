<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloServiciosOptativosFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo_servicios_optativos_file', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_mod_serv_opt')->nullable();
            
            $table->string('path')->nullable();
            $table->string('name')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_mod_serv_opt')->references('id')->on('modulo_servicios_optativos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modulo_servicios_optativos_file');
    }
}
