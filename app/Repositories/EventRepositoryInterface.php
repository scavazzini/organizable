<?php

namespace App\Repositories;

use App\Event;
use App\User;

interface EventRepositoryInterface
{
    public function create($events, User $user): void;
    public function getEventByUuid(string $uuid): ?Event;
    public function getAllEvents(User $user): array;
    public function getEventsInRange(\DateTime $from, \DateTime $to, User $user): array;
    public function updateEvent(Event $event, array $data): void;
    public function delete(Event $event): void;
    public function unlinkUser(Event $event, User $user): void;
}
