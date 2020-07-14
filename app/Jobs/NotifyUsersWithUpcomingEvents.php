<?php

namespace App\Jobs;

use App\Mail\UpcomingEventsMail;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NotifyUsersWithUpcomingEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $days;

    public function __construct(int $days = 3)
    {
        $this->days = $days;
    }

    public function handle(UserRepositoryInterface $userRepository)
    {
        $users = $userRepository->getUsersWithUpcomingEvents($this->days);

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new UpcomingEventsMail($user, $user->events->all()));
        }
    }
}
