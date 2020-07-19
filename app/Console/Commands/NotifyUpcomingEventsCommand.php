<?php

namespace App\Console\Commands;

use App\Jobs\NotifyUsersWithUpcomingEvents;
use Illuminate\Console\Command;

class NotifyUpcomingEventsCommand extends Command
{
    protected $signature = 'users:notify-upcoming';
    protected $description = 'Notify users with upcoming events by email';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $confirmation = $this->confirm('Are you sure you want to notify the users by email?');

        if ($confirmation === false) {
            $this->comment('Cancelled.');
            return 1;
        }

        NotifyUsersWithUpcomingEvents::dispatch(3);
        return 0;
    }
}
