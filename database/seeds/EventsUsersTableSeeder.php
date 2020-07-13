<?php

use App\Event;
use App\Repositories\EventRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventsUsersTableSeeder extends Seeder
{
    public function run(EventRepositoryInterface $eventRepository)
    {
        $faker = Faker\Factory::create();

        $user = new User([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => bcrypt('password'),
        ]);
        $user->save();

        $eventRepository->createEvent(new Event([
            'title' => $faker->sentence,
            'description' => $faker->text,
            'start_at' => Carbon::today(),
            'end_at' => Carbon::today()->addDays(7),
        ]), $user);
    }
}
