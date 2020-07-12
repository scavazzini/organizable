<?php

namespace App\Repositories;

use App\Event;
use App\User;

interface EventRepositoryInterface
{
    public function createEvent(Event $event, User $user): void;
    public function createEvents(array $events, User $user): void;
}
