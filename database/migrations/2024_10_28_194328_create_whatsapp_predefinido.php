<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappPredefinido extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_predefinido', function (Blueprint $table) {
            $table->id();

            $table->longtext('texto')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();

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
        Schema::dropIfExists('whatsapp_predefinido');
    }
}
