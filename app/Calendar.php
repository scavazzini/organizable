<?php

namespace App;

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
}
