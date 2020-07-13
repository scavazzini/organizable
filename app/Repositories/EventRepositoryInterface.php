<?php

namespace App\Repositories;

use App\Event;
use App\User;

interface EventRepositoryInterface
{
    public function createEvent(Event $event, User $user): void;
    public function createEvents(array $events, User $user): void;
    public function getEventByUuid(string $uuid): ?Event;
    public function getAllEvents(User $user): array;
    public function getEventsInRange(\DateTime $from, \DateTime $to, User $user): array;
}
