<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default.Comentarios', function (Blueprint $table) {
            $table->increments('idComentario')->first();
            $table->integer('idUsuario');
            $table->integer('idComentarioPadre')->nullable()->index('fki_idComentarioPadre_Comentarios');
            $table->integer('idPost');
            $table->integer('idAudio');
            $table->timestampTz('FechaCreacion')->nullable();
            $table->bigInteger('Likes')->nullable();
            $table->bigInteger('Dislikes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default.Comentarios');
    }
}
