<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Place::class, function (Faker $faker) {
    return [
        'title' => $faker->text($min = 5, $max = 10),
        'about' => $faker->text(200),
        'lat' => $faker->latitude($min = -55, $max = 83), 
        'lng' => $faker->longitude($min = -168, $max = 174),
        'type' => $faker->randomElement([111, 112, 222, 223, 333, 334])
        

    ];
});
