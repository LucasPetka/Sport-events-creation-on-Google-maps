<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Place::class, function (Faker $faker) {

    $startingDate = $faker->dateTimeThisYear('+1 month');
    return [
        'title' => $faker->text($min = 10, $max = 20),
        'about' => $faker->text($min = 150, $max = 300),
        'lat' => $faker->latitude($min = 52.278645377656964, $max = 53.02182837230083), 
        'lng' => $faker->longitude($min = -2.3149357594216013, $max = 0.22153274643777365),
        'type' => $faker->randomElement([111, 222, 333, 444, 555, 666, 777, 888]),
        'paid' => $faker->randomElement([1, 0]),
        'highlighted' => null,
        'highlight_valid' => null,
    ];
});
