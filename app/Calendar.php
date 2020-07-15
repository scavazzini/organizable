<?php

namespace App;

use Sabre\VObject\Component\VCalendar;
use Sabre\VObject\Reader;

class Calendar
{
    private $events;

    public function __construct(array $events)
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
                'UID' => $event->id,
                'SUMMARY' => $event->title,
                'DESCRIPTION' => $event->description,
                'DTSTART' => $event->start_at,
                'DTEND' => $event->end_at,
            ]);
        }

        return $vcalendar->serialize();
    }

    public static function fromICalendar(string $ical): self
    {
        $events = [];
        $vcalendar = Reader::read($ical);

        foreach($vcalendar->VEVENT as $event) {
            array_push($events, new Event([
                'title' => (string) $event->SUMMARY,
                'description' => (string) $event->DESCRIPTION,
                'start_at' => $event->DTSTART,
                'end_at' => $event->DTEND,
            ]));
        }

        return new self($events);
    }
}
