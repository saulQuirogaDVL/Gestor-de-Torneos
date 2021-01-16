<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJugadoresDota2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jugadores_dota_2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_Equipo');
            $table->foreign('codigo_Equipo')->references('id')->on('equipos_dota_2');
            $table->string('nickname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jugadores_dota_2');
    }
}
