<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDefaultAudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default.Audios', function (Blueprint $table) {
            $table->foreign(['idVisibilidad'], 'idVisibilidad_Visibilidad')->references(['idVisibilidad'])->on('default.Visibilidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default.Audios', function (Blueprint $table) {
            $table->dropForeign('idVisibilidad_Visibilidad');
        });
    }
}
