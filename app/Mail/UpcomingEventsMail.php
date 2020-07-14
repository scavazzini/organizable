<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpcomingEventsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $events;

    public function __construct(User $user, array $events)
    {
        $this->user = $user;
        $this->events = $events;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject(env('APP_NAME') . ': You have some upcoming events')
            ->markdown('mails.upcoming');
    }
}
