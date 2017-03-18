<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SpyCodesWordRevealed implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $wordKey;

    public $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($wordKey, $id)
    {
        $this->wordKey = $wordKey;
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['charades'];
    }
}
