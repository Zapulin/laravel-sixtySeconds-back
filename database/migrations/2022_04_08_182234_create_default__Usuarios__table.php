<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default.Usuarios', function (Blueprint $table) {
            $table->increments('idUsuario')->first();
            $table->text('Nombre')->nullable();
            $table->text('Nick')->nullable();
            $table->date('FechaNacimiento')->nullable();
            $table->text('Nick')->nullable();
            $table->text('Email')->nullable();
            $table->integer('idAudioPresentacion')->nullable()->index('fki_idAudioPresentacion_Audios');
            $table->text('Password')->nullable();
            $table->text('ImagenPerfilUrl')->nullable();
            $table->text('ImagenFondoUrl')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default.Usuarios');
    }
}
