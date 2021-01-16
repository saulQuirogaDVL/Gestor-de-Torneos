<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartidaWowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partida_wow', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idsala');
            $table->foreign('idsala')->references('id')->on('salaswow');
            $table->integer('fase');
            /*$table->unsignedBigInteger('idmitica');
            $table->foreign('idmitica')->reference('id')->on('mitica_wow');
            $table->integer('nivel_piedra');*/
            $table->unsignedBigInteger('idequipo1');
            $table->foreign('idequipo1')->references('id')->on('equipos_wow');
            $table->unsignedBigInteger('idequipo2');
            $table->foreign('idequipo2')->references('id')->on('equipos_wow');
            $table->integer('nroPartida');
            /*$table->unsignedBigInteger('idafijos_fech');
            $table->foreign('idafijos_fech')->reference('id')->on('afijos_fecha_wow');*/
            /*$table->date('fecha');
            $table->unsignedBigInteger('idganador');
            $table->foreign('idganador')->references('id')->on('equipos_wow');
            $table->unsignedBigInteger('idperdedor');
            $table->foreign('idperdedor')->references('id')->on('equipos_wow');
            $table->string("detalles");
            $table->integer('numero_muertes');
            $table->string("completada_equipo_1");
            $table->string("completada_equipo_2");*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partida_wow');
    }
}
