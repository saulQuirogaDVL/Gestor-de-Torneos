<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartidaWowMiticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partida_wow_mitics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idpartida');
            $table->foreign('idpartida')->references('id')->on('partida_wow');
            $table->integer('mitica');
            $table->date('fechaEncuentro');
            $table->time('horaEncuentro');
            $table->time('tiempoMiticaIdEquipo1');
            $table->integer('numeroMuertesEquipo1');
            $table->string('terminado1');
            $table->time('tiempoMiticaIdEquipo2');
            $table->integer('numeroMuertesEquipo2');
            $table->string('terminado2');
            $table->integer('ganadorIdEquipo');
            $table->integer('perdedorIdEquipo');
            $table->integer('empate');
            $table->string('detalles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partida_wow_mitics');
    }
}
