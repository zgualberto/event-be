<?php

use Faker\Generator as Faker;

$factory->define(App\Event::class, function (Faker $faker) {
    return [
        'title' => $faker->text(50),
        'edate'  => $faker->date('Y-m-d')
    ];
});
