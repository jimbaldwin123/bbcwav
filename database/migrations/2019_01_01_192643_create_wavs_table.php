<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWavsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wavs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('location');
            $table->string('description');
            $table->integer('secs');
            $table->string('category');
            $table->string('CDNumber');
            $table->string('CDName');
            $table->integer('tracknum');
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
        Schema::dropIfExists('wavs');
    }
}
