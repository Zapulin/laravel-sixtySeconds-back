<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDefaultComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default.Comentarios', function (Blueprint $table) {
            $table->foreign(['idComentarioPadre'], 'idComentarioPadre_Comentarios')->references(['idComentario'])->on('default.Comentarios');
            $table->foreign(['idUsuario'], 'idUsuario_Usuarios')->references(['idUsuario'])->on('default.Usuarios');
            $table->foreign(['idPost'], 'idPost_Post')->references(['idPost'])->on('default.Post');
            $table->foreign(['idAudio'], 'idAudio_Audios')->references(['idAudio'])->on('default.Audios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default.Comentarios', function (Blueprint $table) {
            $table->dropForeign('idComentarioPadre_Comentarios');
            $table->dropForeign('idUsuario_Usuarios');
            $table->dropForeign('idPost_Post');
            $table->dropForeign('idAudio_Audios');
        });
    }
}
