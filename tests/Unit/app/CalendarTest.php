<?php

namespace Tests\Unit\app;

use App\Calendar;
use App\Event;
use Sabre\VObject\Component\VCalendar;
use Sabre\VObject\Reader;
use Tests\TestCase;

class CalendarTest extends TestCase
{

    public function testEventsShouldBeAdd()
    {
        $events = factory(Event::class, 2)->make()->all();
        $calendar = new Calendar($events);

        $this->assertEquals(count($events), count($calendar->getEvents()));
    }

    public function testConvertCalendarToICalendar()
    {
        $events = factory(Event::class, 2)->make()->all();
        $iCalendar = (new Calendar($events))->toICalendar();
        $vcalendar = Reader::read($iCalendar);

        $this->assertEquals(count($events), $vcalendar->VEVENT->count());
        for ($i = 0; $i < count($events); $i++) {
            self::assertEquals($events[$i]->title, (string) $vcalendar->VEVENT[$i]->SUMMARY);
            self::assertEquals($events[$i]->description, (string) $vcalendar->VEVENT[$i]->DESCRIPTION);
            self::assertEquals($events[$i]->start_at, $vcalendar->VEVENT[$i]->DTSTART->getDateTime());
            self::assertEquals($events[$i]->end_at, $vcalendar->VEVENT[$i]->DTEND->getDateTime());
        }
    }

    public function testConvertICalendarToCalendar()
    {
        $vcalendar = new VCalendar();
        foreach (factory(Event::class, 2)->make()->all() as $event) {
            $vcalendar->add('VEVENT', [
                'UID' => $event->id,
                'SUMMARY' => $event->title,
                'DESCRIPTION' => $event->description,
                'DTSTART' => $event->start_at,
                'DTEND' => $event->end_at,
            ]);
        }
        $iCalendar = $vcalendar->serialize();

        $calendar = Calendar::fromICalendar($iCalendar);
        $events = $calendar->getEvents();

        $this->assertEquals($vcalendar->VEVENT->count(), count($events));
        for ($i = 0; $i < count($events); $i++) {
            self::assertEquals((string) $vcalendar->VEVENT[$i]->SUMMARY, $events[$i]->title);
            self::assertEquals((string) $vcalendar->VEVENT[$i]->DESCRIPTION, $events[$i]->description);
            self::assertEquals($vcalendar->VEVENT[$i]->DTSTART->getDateTime(), $events[$i]->start_at);
            self::assertEquals($vcalendar->VEVENT[$i]->DTEND->getDateTime(), $events[$i]->end_at);
        }
    }
}
