<?php

namespace Tests\Unit\app;

use App\Event;
use App\Invite;
use App\Repositories\EventRepositoryInterface;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InviteTest extends TestCase
{
    use RefreshDatabase;

    private $eventRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventRepository = $this->app->make(EventRepositoryInterface::class);
    }

    public function testInviteParsing()
    {
        // Prepare sender, recipient and event
        $sender = factory(User::class)->create();
        $event = factory(Event::class)->make();
        $this->eventRepository->create($event, $sender);
        $recipient = factory(User::class)->create();

        // Create new invite
        $invite = new Invite($sender, $event, $recipient);

        // Reconstruct Invite from generated JWT token
        $reconstructedInvite = Invite::parse($invite->__toString());

        // Assert reconstruction success
        $this->assertEquals($event->id, $reconstructedInvite->getEvent()->id);
        $this->assertEquals($sender->id, $reconstructedInvite->getSender()->id);
        $this->assertEquals($recipient->id, $reconstructedInvite->getRecipient()->id);
    }
}
