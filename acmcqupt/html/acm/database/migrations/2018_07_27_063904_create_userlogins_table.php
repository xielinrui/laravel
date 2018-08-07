<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserloginsTable extends Migration
{
    public function up()
    {
        Schema::create('userlogins', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('schoolid')->unique();
            $table->integer('ci');
            $table->integer('status');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('userlogins');
    }
}
