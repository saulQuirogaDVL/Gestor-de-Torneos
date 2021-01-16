<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJugadoresPersonajeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jugadores_personaje', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idpartida');
            $table->foreign('idpartida')->references('id')->on('partida_wow');
            $table->unsignedBigInteger('idjugador');
            $table->foreign('idjugador')->references('id')->on('jugadores_wow');
            $table->string('nombre');
            $table->integer('mitica');
            $table->string('especializacion');
            $table->string('clase');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jugadores_personaje');
    }
}
