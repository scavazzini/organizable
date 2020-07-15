<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->text,
        'start_at' => Carbon::today(),
        'end_at' => Carbon::today()->addDays(7),
    ];
});
