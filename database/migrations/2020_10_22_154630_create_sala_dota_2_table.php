<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaDota2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sala_dota_2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_Usuario');
            $table->foreign('codigo_Usuario')->references('id')->on('users');
            $table->string('nombre_Torneo');
            $table->text('logo')->nullable();
            $table->string('tipo_Eliminacion');
            $table->string('modo_Juego');
            $table->integer('numero_Equipos');
            $table->string('equipo_Ganador');
            $table->date('fecha_Creacion');
            $table->boolean('estado');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sala_dota_2');
    }
}
