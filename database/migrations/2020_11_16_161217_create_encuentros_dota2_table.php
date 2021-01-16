<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncuentrosDota2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encuentros_dota2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_Sala');
            $table->foreign('codigo_Sala')->references('id')->on('sala_dota_2');
            $table->string('equipo_1');
            $table->string('equipo_2');
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
        Schema::dropIfExists('encuentros_dota2');
    }
}
