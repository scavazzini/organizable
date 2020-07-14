<?php

use App\Jobs\NotifyUsersWithUpcomingEvents;
use Illuminate\Support\Facades\Artisan;

Artisan::command('users:notify-upcoming', function () {
    if ($this->confirm('Are you sure you want to notify the users by email?')) {
        (new NotifyUsersWithUpcomingEvents(3))::dispatch();
    }
    else {
        $this->comment('Cancelled.');
    }
})->describe('Notify users with upcoming events by email');
