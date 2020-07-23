<?php

namespace App\Mail;

use App\Event;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GuestJoinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $guest;
    public $event;

    public function __construct(User $guest, Event $event)
    {
        $this->guest = $guest;
        $this->event = $event;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject(env('APP_NAME') . ": {$this->guest->name} joined '{$this->event->title}'")
            ->markdown('mails.guest-joined');
    }
}
