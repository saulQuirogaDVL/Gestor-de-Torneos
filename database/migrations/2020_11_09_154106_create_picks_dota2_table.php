<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicksDota2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picks_dota2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_DetalleP');
            $table->foreign('codigo_DetalleP')->references('id')->on('detalles_partida_dota2');
            $table->string('equipo');
            $table->integer('numeroPick');
            $table->integer('personajePick');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('picks_dota2');
    }
}
