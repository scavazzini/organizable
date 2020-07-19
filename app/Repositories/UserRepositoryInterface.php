<?php

namespace App\Repositories;

use App\User;

interface UserRepositoryInterface
{
    public function getUsersWithUpcomingEvents(int $days): array;
    public function getByUuid(string $uuid): ?User;
    public function updateUser(User $user, array $data): void;
    public function updatePassword(User $user, string $newPassword): void;
}
