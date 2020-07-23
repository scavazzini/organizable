<?php

namespace App\Repositories\Eloquent;

use App\NotificationType;
use App\Repositories\UserRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function getUsersWithUpcomingEvents(int $days, bool $onlyNotifiableUsers = true): array
    {
        $from = Carbon::today()->startOfDay();
        $to = $from->copy()->addDays($days)->endOfDay();

        $queryBuilder = User::query()
            ->whereHas('events', function($query) use ($from, $to) {
                $query->whereBetween('start_at', [$from, $to]);
            })
            ->with(['events' => function($query) use ($to, $from) {
                $query->whereBetween('start_at', [$from, $to]);
            }]);

        if ($onlyNotifiableUsers) {
            $queryBuilder->whereHas('notification_types', function($query) {
                $query->where('notification_types.id', '=', NotificationType::UPCOMING_EVENTS);
            });
        }

        return $queryBuilder->get()->all();
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

    public function getAll(): array
    {
        return User::all()->all();
    }

    public function isNotifiableBy(User $user, string $notificationId): bool
    {
        $notificationExists = $user->notification_types()->find($notificationId);
        return is_a($notificationExists, NotificationType::class);
    }

    public function addNotification(User $user, string $notificationId): void
    {
        $notificationType = NotificationType::findOrFail($notificationId);
        $user->notification_types()->syncWithoutDetaching($notificationType);
    }

    public function removeNotification(User $user, string $notificationId): void
    {
        $notificationType = NotificationType::findOrFail($notificationId);
        $user->notification_types()->detach($notificationType);
    }

    public function clearNotifications(User $user): void
    {
        $user->notification_types()->detach();
    }
}
