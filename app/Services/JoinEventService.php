<?php

namespace App\Services;

use App\Events\GuestJoined;
use App\Invite;
use App\Repositories\EventRepositoryInterface;
use App\User;

class JoinEventService
{
    /** @var EventRepositoryInterface */
    private $eventRepository;

    public function __construct()
    {
        $this->eventRepository = app()->make(EventRepositoryInterface::class);
    }

    public function join(User $user, Invite $invite)
    {
        $event = $invite->getEvent();
        $this->eventRepository->linkUser($event, $user);

        event(new GuestJoined($user, $event));
    }
}
