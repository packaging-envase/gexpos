<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmpresa extends Migration
{
    public function up()
    {
        Schema::table('empresa', function (Blueprint $table) {

            // columna
            $table->unsignedBigInteger('id_user')->nullable()->after('id');

            // foreign key
            $table->foreign('id_user')
                  ->references('id')
                  ->on('users'); // opcional
        });
    }

    public function down()
    {
        Schema::table('empresa', function (Blueprint $table) {

            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });
    }
}
