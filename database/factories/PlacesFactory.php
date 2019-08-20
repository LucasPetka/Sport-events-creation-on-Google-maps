<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Place::class, function (Faker $faker) {
    return [
        'title' => $faker->text($min = 5, $max = 10),
        'about' => $faker->text(200),
        'lat' => $faker->latitude($min = -90, $max = 90), 
        'lng' => $faker->longitude($min = -180, $max = 180),
        'type' => $faker->text(10)

    ];
});
