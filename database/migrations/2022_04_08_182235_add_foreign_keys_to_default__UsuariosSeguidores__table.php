<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDefaultUsuariosSeguidoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default.UsuariosSeguidores', function (Blueprint $table) {
            $table->foreign(['idSeguidor'], 'idSeguidor_Usuarios')->references(['idUsuario'])->on('default.Usuarios');
            $table->foreign(['idUsuario'], 'idUsuario_Usuarios')->references(['idUsuario'])->on('default.Usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default.UsuariosSeguidores', function (Blueprint $table) {
            $table->dropForeign('idSeguidor_Usuarios');
            $table->dropForeign('idUsuario_Usuarios');
        });
    }
}
