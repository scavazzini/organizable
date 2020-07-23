<?php

namespace App\Listeners;

use App\Events\GuestJoined;
use App\Mail\GuestJoinedMail;
use App\NotificationType;
use App\Repositories\UserRepositoryInterface;
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
        $owner = $guestJoined->owner;
        $guest = $guestJoined->guest;
        $event = $guestJoined->event;
        $userRepository = app()->make(UserRepositoryInterface::class);

        $isNotifiable = $userRepository->isNotifiableBy($owner, NotificationType::GUEST_JOINED);

        if ($isNotifiable) {
            $mail = new GuestJoinedMail($guest, $event);
            Mail::to($owner->email)->queue($mail);
        }
    }
}
