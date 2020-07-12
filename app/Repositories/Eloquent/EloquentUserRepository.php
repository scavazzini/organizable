<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserRepositoryInterface;
use App\User;
use Carbon\Carbon;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function getUsersWithUpcomingEvents(int $days): array
    {
        $from = Carbon::today()->startOfDay();
        $to = $from->copy()->addDays($days)->endOfDay();

        return User::query()
            ->whereHas('events', function($query) use ($from, $to) {
                $query->whereBetween('start_at', [$from, $to]);
            })
            ->with(['events' => function($query) use ($to, $from) {
                $query->whereBetween('start_at', [$from, $to]);
            }])
            ->get()
            ->all();
    }
}
