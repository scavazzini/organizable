<?php

namespace App\Repositories;

use App\Event;
use App\User;

interface EventRepositoryInterface
{
    public function create($events, User $user): void;
    public function getByUuid(string $uuid): ?Event;
    public function getAll(User $user): array;
    public function getInRange(\DateTime $from, \DateTime $to, User $user): array;
    public function update(Event $event, array $data): void;
    public function delete(Event $event): void;
    public function unlinkUser(Event $event, User $user): void;
}
