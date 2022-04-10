<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDefaultTematicaPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default.TematicaPost', function (Blueprint $table) {
            $table->foreign(['idPost'], 'idPost_Post')->references(['idPost'])->on('default.Post');
            $table->foreign(['idTematica'], 'idTematica_Tematica')->references(['idTematica'])->on('default.Tematica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default.TematicaPost', function (Blueprint $table) {
            $table->dropForeign('idPost_Post');
            $table->dropForeign('idTematica_Tematica');
        });
    }
}
