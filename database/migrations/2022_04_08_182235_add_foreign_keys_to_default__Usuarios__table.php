<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDefaultUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default.Usuarios', function (Blueprint $table) {
            $table->foreign(['idAudioPresentacion'], 'idAudioPresentacion_Audios')->references(['idAudio'])->on('default.Audios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default.Usuarios', function (Blueprint $table) {
            $table->dropForeign('idAudioPresentacion_Audios');
        });
    }
}
