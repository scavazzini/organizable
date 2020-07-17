<?php

namespace Tests\Feature\app\Repositories\Eloquent;

use App\Event;
use App\Repositories\Eloquent\EloquentCalendarRepository;
use App\Repositories\Eloquent\EloquentEventRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentCalendarRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $eventRepository;
    private $calendarRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventRepository = new EloquentEventRepository();
        $this->calendarRepository = new EloquentCalendarRepository($this->eventRepository);
    }

    public function testShouldGetAMonthCalendar()
    {
        $pastDate = Carbon::now()->subMonths(2);
        $currentDate = Carbon::now();
        $futureDate = Carbon::now()->addMonths(2);
        $user = factory(User::class)->create();

        $pastEvent = factory(Event::class)->make([
            'start_at' => $pastDate,
            'end_at' => $pastDate,
        ]);

        $currentMonthEvent = factory(Event::class)->make([
            'start_at' => $currentDate,
            'end_at' => $currentDate,
        ]);

        $futureEvent = factory(Event::class)->make([
            'start_at' => $futureDate,
            'end_at' => $futureDate,
        ]);

        $this->eventRepository->create([$pastEvent, $currentMonthEvent, $futureEvent], $user);

        $currentMonthCalendar = $this->calendarRepository
            ->getCalendarFromMonth($currentDate->year, $currentDate->month, $user);

        $this->assertCount(1, $currentMonthCalendar->getEvents());
        $this->assertEquals($currentMonthEvent->id, $currentMonthCalendar->getEvents()[0]->id);
    }
}
