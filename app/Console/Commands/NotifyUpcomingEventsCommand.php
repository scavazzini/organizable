<?php

namespace App\Console\Commands;

use App\Jobs\NotifyUsersWithUpcomingEvents;
use Illuminate\Console\Command;

class NotifyUpcomingEventsCommand extends Command
{
    protected $signature = 'users:notify-upcoming {--queue : Queue this action}';
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

        $notifyJob = new NotifyUsersWithUpcomingEvents(3);

        if ($this->option('queue')) {
            dispatch($notifyJob);
            return 0;
        }

        $bar = $this->output->createProgressBar();

        $notifyJob->setOnStart(function($users) use ($bar) {
            $this->line(" Queuing emails...");
            $bar->setMaxSteps($users);
            $bar->start();
        });

        $notifyJob->setOnUpdate(function () use ($bar) {
            $bar->advance();
        });

        $notifyJob->setOnFinish(function () use ($bar) {
            $bar->finish();
            $this->line(' Done.' . PHP_EOL);
        });

        dispatch_now($notifyJob);

        return 0;
    }
}
