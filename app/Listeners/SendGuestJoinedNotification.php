<?php

namespace App\Listeners;

use App\Events\GuestJoined;
use App\Mail\GuestJoinedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendGuestJoinedNotification implements ShouldQueue
{
    public function __construct()
    {
        //
    }

   public function handle(GuestJoined $guestJoined)
    {
        $mail = new GuestJoinedMail($guestJoined->guest, $guestJoined->event);
        Mail::to($guestJoined->owner->email)->queue($mail);
    }
}
