<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->jobTitle,
        'statement' => $faker->text(),
        'start_at' => $faker->dateTime,
        'end_at' => $faker->dateTime
    ];
});
