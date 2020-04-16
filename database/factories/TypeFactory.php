<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Type::class, function (Faker $faker) {

    return [
        'name' => $faker->text($min = 10, $max = 20),
        'image' => $faker->text($min = 20, $max = 30),
        'image_h' => $faker->text($min = 20, $max = 30),
    ];
});
