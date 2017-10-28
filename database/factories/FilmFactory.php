<?php

use Faker\Generator as Faker;

$factory->define(App\Film::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->unique()->safeEmail,
    ];
});
