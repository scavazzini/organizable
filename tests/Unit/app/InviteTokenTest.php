<?php

namespace Tests\Unit\app;

use App\Event;
use App\InviteToken;
use App\Repositories\EventRepositoryInterface;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InviteTokenTest extends TestCase
{
    use RefreshDatabase;

    private $eventRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventRepository = $this->app->make(EventRepositoryInterface::class);
    }

    public function testTokenParsing()
    {
        // Prepare sender, recipient and event
        $sender = factory(User::class)->create();
        $event = factory(Event::class)->make();
        $this->eventRepository->create($event, $sender);
        $recipient = factory(User::class)->create();

        // Create new token
        $token = new InviteToken($sender, $event, $recipient);

        // Reconstruct InviteToken from generated JWT token
        $reconstructedToken = InviteToken::parse($token->__toString());

        // Assert reconstruction success
        $this->assertEquals($event->id, $reconstructedToken->getEvent()->id);
        $this->assertEquals($sender->id, $reconstructedToken->getSender()->id);
        $this->assertEquals($recipient->id, $reconstructedToken->getRecipient()->id);
    }
}
