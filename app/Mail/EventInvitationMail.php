<?php

namespace App\Mail;

use App\Event;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class EventInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $sender;
    public $joinUrl;
    public $tokenLifespan;

    public function __construct(Event $event, User $sender, string $token, int $tokenLifespan)
    {
        $this->event = $event;
        $this->sender = $sender;
        $this->joinUrl = URL::to("/events/{$event->id}?invite={$token}");
        $this->tokenLifespan = $tokenLifespan;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject("{$this->sender->name} is inviting you to {$this->event->title}")
            ->markdown('mails.invite');
    }
}
