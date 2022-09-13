<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Entry;

class LiveEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $entry;

    public function __construct(Entry $entry=null)
    {
        $this->entry = $entry ? $entry : Entry::inRandomOrder()->take(1)->first();
    }

    public function broadcastWith(){
        return [
            'type'   => 'entry.new',
            'entry'  => $this->entry, // Entry::inRandomOrder()->take(1)->first(),
        ];
    }


    public function broadcastOn()
    {
        return new Channel('live');
    }
}
