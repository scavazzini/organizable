<?php

namespace Tests\Feature\app\Repositories\Eloquent;

use App\Event;
use App\Repositories\Eloquent\EloquentEventRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentEventRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $eventRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventRepository = new EloquentEventRepository();
    }

    public function testShouldCreateEvents()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->make();

        $this->eventRepository->create($event, $user);

        $this->assertDatabaseCount('events', 1);
        $this->assertDatabaseCount('event_user', 1);
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'start_at' => $event->start_at,
            'end_at' => $event->end_at,
        ]);
    }

    public function testShouldGetEventByUuid()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->make();
        $this->eventRepository->create($event, $user);

        $eventFound = $this->eventRepository->getByUuid($event->id);

        $this->assertEquals($event->id, $eventFound->id);
    }

    public function testShouldGetAllEventsOfAUser()
    {
        $user1 = factory(User::class)->create();
        $this->eventRepository->create(factory(Event::class, 10)->make()->all(), $user1);

        $user2 = factory(User::class)->create();
        $this->eventRepository->create(factory(Event::class, 5)->make()->all(), $user2);

        $eventsUser1 = $this->eventRepository->getAll($user1);
        $eventsUser2 = $this->eventRepository->getAll($user2);

        $this->assertCount(10, $eventsUser1);
        $this->assertCount(5, $eventsUser2);
    }

    public function testShouldGetEventsInRange()
    {
        $pastDate = Carbon::now()->subMonth();
        $date = Carbon::now();

        $user = factory(User::class)->create();

        $pastEvent = factory(Event::class)->make([
            'start_at' => $pastDate,
            'end_at' => $pastDate,
        ]);

        $event = factory(Event::class)->make([
            'start_at' => $date,
            'end_at' => $date,
        ]);

        $this->eventRepository->create([$pastEvent, $event], $user);

        $events = $this->eventRepository->getInRange($date, $date, $user);

        $this->assertCount(1, $events);
        $this->assertEquals($event->id, $events[0]->id);
    }

    public function testShouldUpdateEvent()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->make();
        $this->eventRepository->create($event, $user);

        $newData = [
            'title' => 'New event title',
            'description' => 'New event description',
            'start_at' => Carbon::today()->addWeek(),
            'end_at' => Carbon::today()->addMonth(),
        ];
        $this->eventRepository->update($event, $newData);

        $this->assertDatabaseHas('events', $newData);
        $this->assertEquals($newData['title'], $event->title);
        $this->assertEquals($newData['description'], $event->description);
        $this->assertEquals($newData['start_at'], $event->start_at);
        $this->assertEquals($newData['end_at'], $event->end_at);
    }

    public function testShouldDeleteEvent()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->make();
        $this->eventRepository->create($event, $user);

        $this->eventRepository->delete($event);

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function testShouldUnlinkUserAndEvent()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->make();
        $this->eventRepository->create($event, $user);

        $this->eventRepository->unlinkUser($event, $user);

        $this->assertDatabaseHas('events', ['id' => $event->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('event_user', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    public function testShouldLinkUserAndEvent()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create();

        $this->eventRepository->linkUser($event, $user);

        $this->assertDatabaseHas('events', ['id' => $event->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('event_user', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }
}
