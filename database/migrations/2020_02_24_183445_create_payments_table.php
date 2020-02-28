<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('person_id')->unsigned();
            $table->integer('place_id')->unsigned();
            $table->string('status');
            $table->string('payer_name');
            $table->string('payer_email');
            $table->string('name');
            $table->string('amount');

            $table->foreign('person_id')->references('id')->on('users');
            $table->foreign('place_id')->references('id')->on('places');

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
        Schema::dropIfExists('payments');
    }
}
