<?php

namespace App\Observers;

use App\NotificationType;
use App\User;

class UserObserver
{
    public function created(User $user)
    {
        $user->notification_types()->attach([
            NotificationType::UPCOMING_EVENTS,
            NotificationType::GUEST_JOINED,
        ]);
    }
}
