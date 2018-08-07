<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gamename');
            $table->string('fuzeren');
            $table->string('fuzerenphone');
            $table->dateTime('gametime');
            $table->text('address');
            $table->string('origanizetion');
            $table->text('guize');
            $table->integer('number');
            $table->text('news');
            $table->integer('resultid');
            $table->integer('baomingid');
            $table->integer('gamestatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
