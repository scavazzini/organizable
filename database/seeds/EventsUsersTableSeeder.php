<?php

use App\Event;
use App\Repositories\EventRepositoryInterface;
use App\User;
use Illuminate\Database\Seeder;

class EventsUsersTableSeeder extends Seeder
{
    public function run(EventRepositoryInterface $eventRepository)
    {
        $user = factory(User::class)->make();
        $event = factory(Event::class)->make();

        $user->save();
        $eventRepository->create($event, $user);
    }
}
