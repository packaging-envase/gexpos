<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exposicion_archivo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_exposicion');
            $table->string('original_name');
            $table->string('tipo');
            $table->string('path');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_exposicion')->references('id')->on('exposicion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exposicion_archivo');
    }
};
