<?php

namespace App;

use Ramsey\Uuid\Uuid;
use Sabre\VObject\Component\VCalendar;

class Calendar
{
    private $events;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function toICalendar(): string
    {
        $vcalendar = new VCalendar();
        $vcalendar->PRODID = env('APP_NAME');

        foreach ($this->events as $event) {
            $vcalendar->add('VEVENT', [
                'UID' => Uuid::uuid4(),
                'SUMMARY' => $event->title,
                'DESCRIPTION' => $event->description,
                'DTSTART' => $event->start_at,
                'DTEND' => $event->end_at,
            ]);
        }

        return $vcalendar->serialize();
    }
}
