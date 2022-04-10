<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultUsuariosSeguidoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default.UsuariosSeguidores', function (Blueprint $table) {
            $table->increments('idUsuariosSeguidores')->first();
            $table->integer('idSeguidor')->nullable()->index('fki_idSeguidor_Usuarios');
            $table->boolean('Bloqueado')->nullable();
            $table->integer('idUsuario')->nullable()->index('fki_idUsuario_Usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default.UsuariosSeguidores');
    }
}
