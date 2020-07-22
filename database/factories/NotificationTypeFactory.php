<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\NotificationType;
use Faker\Generator as Faker;

$factory->define(NotificationType::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->word,
        'description' => $faker->sentence,
    ];
});
