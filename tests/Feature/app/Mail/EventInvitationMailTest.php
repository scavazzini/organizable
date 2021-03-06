<?php

namespace Tests\Feature\app\Mail;

use App\Event;
use App\Invite;
use App\Mail\EventInvitationMail;
use App\Repositories\EventRepositoryInterface;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EventInvitationMailTest extends TestCase
{
    use RefreshDatabase;

    private $eventRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventRepository = $this->app->make(EventRepositoryInterface::class);
    }

    public function testShouldSendInvitationMail()
    {
        // Prepare sender, recipient and event
        $sender = factory(User::class)->create();
        $event = factory(Event::class)->make();
        $this->eventRepository->create($event, $sender);
        $recipient = factory(User::class)->create();

        $invite = new Invite($sender, $event, $recipient);

        // Perform invite with mocked Mail facade
        Mail::fake();
        Mail::to($recipient)->queue(new EventInvitationMail($invite));

        // Assert mailable was queued
        Mail::assertQueued(function (EventInvitationMail $mail) use ($event, $sender, $invite) {
            return $mail->event->id === $event->id &&
                $mail->sender === $sender &&
                $mail->invite === $invite;
        });

        // Assert a message was queued to the recipient
        Mail::assertQueued(EventInvitationMail::class, function ($mail) use ($recipient) {
            return $mail->hasTo($recipient);
        });
    }
}
