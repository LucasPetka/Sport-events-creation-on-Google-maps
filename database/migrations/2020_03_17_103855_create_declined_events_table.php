<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeclinedEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declined_events', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->unsignedInteger('place_id');
            $table->string('title');
            $table->text('about');
            $table->dateTime('time_from');
            $table->dateTime('time_until');
            $table->timestamps();
            $table->unsignedInteger('person_id');

            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('declined_events');
    }
}
