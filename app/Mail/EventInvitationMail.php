<?php

namespace App\Mail;

use App\InviteToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class EventInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $event;
    public $sender;
    public $joinUrl;

    public function __construct(InviteToken $token)
    {
        $this->token = $token;
        $this->event = $token->getEvent();
        $this->sender = $token->getSender();
        $this->joinUrl = URL::to("/events/{$this->event->id}?invite={$token}");
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject("{$this->sender->name} is inviting you to {$this->event->title}")
            ->markdown('mails.invite');
    }
}
