<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDefaultPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default.Post', function (Blueprint $table) {
            $table->foreign(['idAudio'], 'idAudio_Audios')->references(['idAudio'])->on('default.Audios');
            $table->foreign(['idVisibilidad'], 'idVisibilidad_VisibilidadPost')->references(['idVisibilidad'])->on('default.Visibilidad');
            $table->foreign(['idUsuario'], 'idusuario_usuarios')->references(['idUsuario'])->on('default.Usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default.Post', function (Blueprint $table) {
            $table->dropForeign('idAudio_Audios');
            $table->dropForeign('idVisibilidad_VisibilidadPost');
            $table->dropForeign('idusuario_usuarios');
        });
    }
}
