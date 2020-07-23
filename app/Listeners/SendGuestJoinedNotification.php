<?php

namespace App\Listeners;

use App\Events\GuestJoined;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendGuestJoinedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GuestJoined  $event
     * @return void
     */
    public function handle(GuestJoined $event)
    {
        // TODO: Send notification to event owner.
    }
}
