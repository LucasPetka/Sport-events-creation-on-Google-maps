<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AcceptedPlaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accepted_places', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('place_id');
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('users');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accepted_places');
    }
}
