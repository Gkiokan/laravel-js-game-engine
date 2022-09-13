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
use App\Models\Release;

class NewEntryAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $entry;
    public $release;

    public function __construct(Entry $entry, Release $release)
    {
        $this->entry    = $entry;
        $this->release  = $release;
    }

    // public function broadcastWith(){
    //     \Log::info("New Entry created, broadcasting?");
    //     return [
    //         'type'   => 'entry.new',
    //         'entry'  => $this->entry,
    //     ];
    // }

    // public function broadcastOn()
    // {
    //     return new Channel('live');
    // }
}
