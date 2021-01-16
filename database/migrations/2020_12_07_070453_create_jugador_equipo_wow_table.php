<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJugadorEquipoWowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jugador_equipo_wow', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idsala');
            $table->foreign('idsala')->references('id')->on('salaswow');
            $table->unsignedBigInteger('idequipo');
            $table->foreign('idequipo')->references('id')->on('equipos_wow');
            $table->unsignedBigInteger('idjugador');
            $table->foreign('idjugador')->references('id')->on('jugadores_wow');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jugador_equipo_wow');
    }
}
