<?php

namespace App\Repositories\Eloquent;

use App\Event;
use App\Repositories\EventRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\DB;

class EloquentEventRepository implements EventRepositoryInterface
{

    public function createEvent(Event $event, User $user): void
    {
        $this->createEvents([$event], $user);
    }

    public function createEvents(array $events, User $user): void
    {
        DB::beginTransaction();
        try {

            foreach ($events as $event) {
                $event->save();
                $user->events()->attach($event, ['owner' => true]);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Failed to persist events.');
        }
    }
}
