<?php

use App\Event;
use App\Repositories\EventRepositoryInterface;
use App\User;
use Illuminate\Database\Seeder;

class EventsUsersTableSeeder extends Seeder
{
    public function run(EventRepositoryInterface $eventRepository)
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->make();
        $eventRepository->create($event, $user);
    }
}
