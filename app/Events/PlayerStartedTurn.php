<?php

namespace App\Events;

use App\Word;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PlayerStartedTurn implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $word;

    /**
     * PlayerStartedTurn constructor.
     * @param $message
     */
    public function __construct($word)
    {
        $this->word = $word;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['charades'];
//        return new PrivateChannel('charades');
    }
}
