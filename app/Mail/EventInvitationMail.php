<?php

namespace App\Mail;

use App\Event;
use App\InviteToken;
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
    public $token;
    public $joinUrl;

    public function __construct(Event $event, User $sender, InviteToken $token)
    {
        $this->event = $event;
        $this->sender = $sender;
        $this->token = $token;
        $this->joinUrl = URL::to("/events/{$event->id}?invite={$token}");
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject("{$this->sender->name} is inviting you to {$this->event->title}")
            ->markdown('mails.invite');
    }
}
