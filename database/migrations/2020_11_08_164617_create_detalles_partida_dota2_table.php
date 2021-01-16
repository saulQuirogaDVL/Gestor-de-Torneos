<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesPartidaDota2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_partida_dota2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_Encuentro');
            $table->foreign('codigo_Encuentro')->references('id')->on('encuentros_dota2');
            $table->integer('eliminaciones_e1');
            $table->integer('eliminaciones_e2');
            $table->boolean('fasepickban');
            $table->integer('numero_partida');
            $table->string('equipo_Ganador');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_partida_dota2');
    }
}
