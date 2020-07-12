<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getUsersWithUpcomingEvents(int $days): array;
}
