<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Mail\PasswordResetMail;

use Lang;
use Carbon\Carbon;
use URL;
use Config;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $token;
    public $user;

    public function __construct($token=null)
    {
        $this->token = $token;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($user)
    {
        $url = $this->getUrl($this->token, $user->email);

        return (new PasswordResetMail($user, $url))
                ->subject(Lang::get('Reset Password'))
                ->to($user->email);

        // return (new MailMessage)
        //             ->subject('Password Zurücksetzen')
        //             ->greeting('Password Zurücksetzen')
        //             ->line('Hallo ' . $user->displayname . ',')
        //             ->line('')
        //             ->line('Hier kannst du dein Password Zurücksetzen.')
        //             ->line('Der Link ist nur 60 Minuten gültig.')
        //             ->line('')
        //             ->action('Neues Password setzen', $url )
        //             ->salutation('Vielen Dank');
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function getUrl($token=null, $email=null){
        $url = URL::temporarySignedRoute(
            'password.reset',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'email' => $email,
                'token' => $token,
            ]
        );

        $url = str_replace('/api/', '/app/', $url);
        $url = str_replace(config('app.APP_API_URL'), config('app.APP_DASHBOARD_URL'), $url);
        return $url;
    }

}
