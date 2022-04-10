<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default.Post', function (Blueprint $table) {
            $table->increments('idPost')->first();
            $table->integer('idVisibilidad')->nullable()->index('fki_idVisibilidad_VisibilidadPost');
            $table->bigInteger('Visualizaciones')->nullable();
            $table->integer('idUsuario')->nullable()->index('fki_idusuario_usuarios');
            $table->integer('idAudio')->nullable()->index('fki_idAudio_Audios');
            $table->timestampTz('FechaCreacion')->nullable();
            $table->bigInteger('Dislikes')->nullable();
            $table->bigInteger('Likes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default.Post');
    }
}
