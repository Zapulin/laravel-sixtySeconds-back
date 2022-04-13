<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultAudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default.Audios', function (Blueprint $table) {
            $table->increments('idAudio')->first();
            $table->text('Url')->nullable();
            $table->text('Server')->nullable();
            $table->text('ShortUrl')->nullable();
            $table->timestampTz('FechaCreacion')->nullable();
            $table->integer('Tamano')->nullable();
            $table->text('ClaveDesbloqueo')->nullable();
            $table->integer('idVisibilidad')->nullable()->index('fki_idVisibilidad_Visibilidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default.Audios');
    }
}
