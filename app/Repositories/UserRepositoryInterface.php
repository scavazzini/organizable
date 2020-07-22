<?php

namespace App\Repositories;

use App\User;

interface UserRepositoryInterface
{
    public function getUsersWithUpcomingEvents(int $days, bool $onlyNotifiableUsers = true): array;
    public function getAll(): array;
    public function getByUuid(string $uuid): ?User;
    public function updateUser(User $user, array $data): void;
    public function updatePassword(User $user, string $newPassword): void;
    public function isNotifiableBy(User $user, string $notificationId): bool;
    public function addNotification(User $user, string $notificationId): void;
    public function removeNotification(User $user, string $notificationId): void;
    public function clearNotifications(User $user): void;
}
