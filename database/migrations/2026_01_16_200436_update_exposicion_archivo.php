<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExposicionArchivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exposicion_archivo', function (Blueprint $table) {
            $table->boolean('importante')
                  ->default(false)
                  ->after('path'); // ajustar la columna según corresponda
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exposicion_archivo', function (Blueprint $table) {
            $table->dropColumn('importante');
        });
    }
}
