<?php

namespace App\Repositories\Eloquent;

use App\Calendar;
use App\Repositories\CalendarRepositoryInterface;
use App\Repositories\EventRepositoryInterface;
use App\User;
use Carbon\Carbon;

class EloquentCalendarRepository implements CalendarRepositoryInterface
{
    /** @var EventRepositoryInterface $eventRepository */
    private $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function getCalendarFromMonth(int $year, int $month, User $user): Calendar
    {
        $from = Carbon::createFromDate($year, $month)->startOfMonth();
        $to = Carbon::createFromDate($year, $month)->endOfMonth();
        $events = $this->eventRepository->getInRange($from, $to, $user);

        return new Calendar($events);
    }
}
