<?php

namespace App\Observers;

use App\Event;

class EventObserver
{
    public function deleting(Event $event)
    {
        $event->participants()->detach();
    }
}
