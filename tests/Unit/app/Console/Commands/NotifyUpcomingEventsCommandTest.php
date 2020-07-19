<?php

namespace Tests\Unit\app\Console\Commands;

use App\Jobs\NotifyUsersWithUpcomingEvents;
use Tests\TestCase;

class NotifyUpcomingEventsCommandTest extends TestCase
{
    public function testShouldNotifyUsersIfResponseIsYes()
    {
        $this->expectsJobs(NotifyUsersWithUpcomingEvents::class);

        $this->artisan('users:notify-upcoming')
            ->expectsConfirmation('Are you sure you want to notify the users by email?', 'yes')
            ->assertExitCode(0);
    }

    public function testShouldNotNotifyUsersIfResponseIsNo()
    {
        $this->doesntExpectJobs(NotifyUsersWithUpcomingEvents::class);

        $this->artisan('users:notify-upcoming')
            ->expectsConfirmation('Are you sure you want to notify the users by email?', 'no')
            ->expectsOutput('Cancelled.')
            ->assertExitCode(1);
    }
}
