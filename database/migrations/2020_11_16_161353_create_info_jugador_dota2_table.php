<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoJugadorDota2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_jugador_dota2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_DetalleP');
            $table->foreign('codigo_DetalleP')->references('id')->on('detalles_partida_dota2');
            $table->unsignedBigInteger('codigo_Jugador');
            $table->foreign('codigo_Jugador')->references('id')->on('jugadores_dota_2');
            $table->integer('personaje');
            $table->integer('nivel');
            $table->integer('asesinatos');
            $table->integer('muertes');
            $table->integer('asistencias');
            $table->integer('slot1');
            $table->integer('slot2');
            $table->integer('slot3');
            $table->integer('slot4');
            $table->integer('slot5');
            $table->integer('slot6');
            $table->integer('slotJunglas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('info_jugador_dota2');
    }
}
