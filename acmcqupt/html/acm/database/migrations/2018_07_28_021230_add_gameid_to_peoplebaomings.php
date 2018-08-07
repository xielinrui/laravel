<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameidToPeoplebaomings extends Migration
{
    public function up()
    {
        Schema::table('peoplebaomings',function(Blueprint $table){
            $table->integer('gameid');
        });
    }

    public function down()
    {
        Schema::table('peoplebaomings',function(Blueprint $table){
            $table->integer('gameid');
        });
    }
}
