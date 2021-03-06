<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('title');
            $table->text('about');
            $table->decimal('lat', 20, 16);
            $table->decimal('lng', 20, 16);
            $table->unsignedInteger('type');
            $table->string('paid', 1);
            $table->boolean('highlighted')->nullable();
            $table->string('highlight_valid')->nullable();
            $table->timestamps();

            $table->foreign('type')->references('id')->on('types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
