<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Notifications\SendToWCXMonitoringNotification;
use App\Notifications\SendNewEntryAddedToWCXMonitoringNotification;

use Notification;

class NewEntryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $entry = $event->entry;
        $release = $event->release;

        $count = $entry->releases()->count();

        if($count >= 2):
            \Log::info("Release Count of {$count} for {$release->fulltitle}. Abording Event.");
            return;
        endif;

        try {
            broadcast( new \App\Events\LiveEvent($entry) );
        }
        catch( \Exception $e){
            \Log::info("New Entry Listener Broadcast Error: " . $e->getMessage());
        }

        try {
            // \Log::info("Telegram Channel " . env('TELEGRAM_MONITORING_CHAT_ID') );
            Notification::route('telegram', env('TELEGRAM_MONITORING_CHAT_ID'))->notify( new SendNewEntryAddedToWCXMonitoringNotification($entry, $release) );
        }
        catch( \Exception $e){
            \Log::info("Error in Sending Notification to Telegram. " . $e->getMessage());
        }
    }
}
