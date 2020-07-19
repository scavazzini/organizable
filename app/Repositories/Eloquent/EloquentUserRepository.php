<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

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

    public function updateUser(User $user, array $data): void
    {
        $data = Arr::only($data, ['name', 'email']);

        if (isset($data['email']) && $user->email !== $data['email']) {

            // There is an email change, need to check if it's in use.
            $validator = Validator::make($data, ['email' => 'unique:users']);
            if ($validator->fails()) {
                throw new \Exception('Email already in use.');
            }

        }

        $user->update($data);
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        $validator = Validator::make(['password' => $newPassword], [
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            throw new \Exception('New password does not satisfy the requirements.');
        }

        $user->update([
            'password' => bcrypt($newPassword),
        ]);
    }

    public function getByUuid(string $uuid): ?User
    {
        return User::find($uuid);
    }
}
