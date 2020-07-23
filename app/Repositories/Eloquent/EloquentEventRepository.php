<?php

namespace App\Repositories\Eloquent;

use App\Event;
use App\Repositories\EventRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EloquentEventRepository implements EventRepositoryInterface
{

    public function create($events, User $user): void
    {
        if (is_a($events, Event::class)) {
            $events = array($events);
        }

        if (!is_array($events)) {
            throw new \InvalidArgumentException('First argument must be an Event or an array of Events');
        }

        foreach ($events as $event) {
            if (!is_a($event, Event::class)) {
                throw new \InvalidArgumentException('First argument must be an Event or an array of Events');
            }
        }

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

    public function getInRange(\DateTime $from, \DateTime $to, User $user): array
    {
        return Event::query()
            ->whereHas('guests', function(Builder $query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('start_at', '<=', $to)
            ->where('end_at', '>=', $from)
            ->orderBy('start_at')
            ->get()
            ->all();
    }

    public function getAll(User $user): array
    {
        return Event::query()
            ->whereHas('guests', function(Builder $query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('start_at')
            ->get()
            ->all();
    }

    public function getByUuid(string $uuid): ?Event
    {
        return Event::find($uuid);
    }

    public function update(Event $event, array $data): void
    {
        $data = Arr::only($data, ['title', 'description', 'start_at', 'end_at']);

        $validator = Validator::make($data, [
            'title' => 'required',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start'
        ]);
        if ($validator->fails()) {
            throw new \Exception('Event does not satisfy the requirements.');
        }

        $event->update($data);
    }

    public function delete(Event $event): void
    {
        DB::beginTransaction();
        try {
            $event->delete();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Failed to delete event.');
        }
    }

    public function unlinkUser(Event $event, User $user): void
    {
        $event->guests()->detach($user);
    }

    public function linkUser(Event $event, User $user): void
    {
        $event->guests()->syncWithoutDetaching($user);
    }
}
