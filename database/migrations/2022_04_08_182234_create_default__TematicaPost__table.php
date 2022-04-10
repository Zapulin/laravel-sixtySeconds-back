<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultTematicaPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default.TematicaPost', function (Blueprint $table) {
            $table->increments('idTematicaPost')->first();
            $table->integer('idTematica')->nullable()->index('fki_idTematica_Tematica');
            $table->integer('idPost')->nullable()->index('fki_idPost_Post');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default.TematicaPost');
    }
}
