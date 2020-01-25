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
            $table->bigIncrements('id')->unique();
            $table->string('title');
            $table->text('about');
            $table->decimal('lat', 20, 16);
            $table->decimal('lng', 20, 16);
            $table->string('type');
            $table->integer('personid')->unsigned();
            $table->timestamps();

            $table->foreign('personid')->references('id')->on('users');
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
