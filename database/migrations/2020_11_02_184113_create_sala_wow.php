<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaWow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaswow', function (Blueprint $table) {
            $table->id();
            $table->string("nombreSala");
            $table->text("logo");
            $table->unsignedBigInteger('arbitro');
            $table->foreign("arbitro")->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salaswow');
    }
}
