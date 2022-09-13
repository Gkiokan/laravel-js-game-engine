<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramFile;

use App\Models\Entry;
use App\Models\Release;

use Str;

class SendNewEntryAddedToWCXMonitoringNotification extends Notification
{
    use Queueable;

    public $entry;
    public $release;

    public function __construct(Entry $entry, Release $release)
    {
        $this->entry    = $entry;
        $this->release  = $release;
    }

    public function via($notifiable)
    {
        return ["telegram"];
    }

    public function toTelegram($notifiable)
    {        
        $entry = $this->entry;
        $release = $this->release;
        $date  = $entry->created_at->format('d.m.Y');
        $size  = $this->human_filesize($release->size);
        
        $url   = env('APP_URL'); // "https://wcx.test";
        $entryURL = env('APP_URL') . "/detail/" . $entry->uid . "/" . Str::of($entry->name)->slug('-');
        // dd($entry->releases->toArray());

        $telegram = TelegramFile::create()
            // Optional recipient user id.
            ->to(env('TELEGRAM_MONITORING_CHAT_ID'))
            ->content("
{$entry->title}

{$entry->fulltitle}

Group: {$release->group} 
Source: {$release->video_stream}
Codec: {$release->video_codec}
Größe: {$size}
Erstellt: {$date}
            ")            
            ->disableNotification(true)

            // (Optional) Blade template for the content.
            // ->view('notification', ['url' => $url])

            // (Optional) Inline Buttons
            ->button('Go to WCX', $url)
            ->button('View Entry', $entryURL)
            // (Optional) Inline Button with callback. You can handle callback in your bot instance
            // ->buttonWithCallback('Confirm', 'confirm_invoice ' . 1);
            ;

        // foreach($entry->releases as $release):        
            $dlc = $release->crypted_links;

            foreach($dlc as $hoster => $link)
                if($link)
                    $telegram->button($hoster, $link);
        // endforeach;

        // add Cover on condition 
        if($entry->cover)
            $telegram->file($entry->cover, 'photo');

        return $telegram;
    }

    function human_filesize($bytes=null, $dec = 2) 
    {
        if(!$bytes)
            return "n/a";

        $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

}
