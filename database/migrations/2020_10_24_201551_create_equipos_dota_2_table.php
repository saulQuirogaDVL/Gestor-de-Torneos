<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquiposDota2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos_dota_2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_Sala');
            $table->foreign('codigo_Sala')->references('id')->on('sala_dota_2');
            $table->string('nombre_Equipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipos_dota_2');
    }
}
