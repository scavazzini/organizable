<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function created(User $user)
    {
        $user->notification_types()->attach('upcoming-events');
    }
}
