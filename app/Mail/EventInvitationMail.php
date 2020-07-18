<?php

namespace App\Mail;

use App\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class EventInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;
    public $event;
    public $sender;
    public $joinUrl;

    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
        $this->event = $invite->getEvent();
        $this->sender = $invite->getSender();
        $this->joinUrl = URL::to("/events/{$this->event->id}?invite={$invite}");
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject("{$this->sender->name} is inviting you to {$this->event->title}")
            ->markdown('mails.invite');
    }
}
