<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacequeueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('placequeue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('about');
            $table->string('lat');
            $table->string('lng');
            $table->string('type');
            $table->string('personid');
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
        Schema::dropIfExists('placequeue');
    }
}
