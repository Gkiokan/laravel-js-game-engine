<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;

class Notification extends BaseNotification
{
    use Queueable;

    public $title;
    public $message;

    public function __construct($title=null, $message="")
    {
        $this->title   = $title;
        $this->message = $message;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toArray($notifiable){
        return [
            'type'    => 'message',
            'title'   => $this->title,
            'message' => $this->message,
        ];
    }

}
