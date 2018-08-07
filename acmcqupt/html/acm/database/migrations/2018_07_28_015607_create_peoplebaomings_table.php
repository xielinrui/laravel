<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeoplebaomingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peoplebaomings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('baomingid');
            $table->bigInteger('schoolid');
            $table->bigInteger('phone');
            $table->string('email');
            $table->string('xueyuan');
            $table->string('zhuanye');
            $table->string('duiming');
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
        Schema::dropIfExists('peoplebaomings');
    }
}
