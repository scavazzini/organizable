<?php

namespace Tests\Feature\app\Services;

use App\Event;
use App\Events\GuestJoined;
use App\Invite;
use App\Services\JoinEventService;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JoinEventServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestShouldJoinEvent()
    {
        $this->expectsEvents(GuestJoined::class);

        $sender = factory(User::class)->create();
        $guest = factory(User::class)->create();
        $event = factory(Event::class)->create();
        $invite = new Invite($sender, $event, $guest);

        $joinEventService = new JoinEventService();
        $joinEventService->join($guest, $invite);

        $this->assertDatabaseHas('event_user', [
            'user_id' => $guest->id,
            'event_id' => $event->id,
        ]);
    }
}
