<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBansDota2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bans_dota2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_DetalleP');
            $table->foreign('codigo_DetalleP')->references('id')->on('detalles_partida_dota2');
            $table->string('equipo');
            $table->integer('numeroBan');
            $table->integer('personajeBan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bans_dota2');
    }
}
