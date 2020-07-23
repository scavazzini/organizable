<?php

namespace App\Events;

use App\Event;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuestJoined
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $owner;
    public $guest;
    public $event;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $owner, User $guest, Event $event)
    {
        $this->owner = $owner;
        $this->guest = $guest;
        $this->event = $event;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
