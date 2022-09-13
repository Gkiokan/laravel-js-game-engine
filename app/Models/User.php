<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Notifications\VerifyEmail;
use App\Notifications\PasswordResetNotification;

use App\Traits\SaveSettings;

use Auth;
use Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username', 'email', 'email_verified_at', 'password',
        'avatar', 'active', 'role', 'gain_access',
        'secret', 'g2fa',
    ];

    protected $hidden = [
        'password', 'remember_token',
        'settingSettings', 'profileSettings',
    ];

    protected $appends = [
        'settings',
        'profile',
        'displayname',
        'chatID',
        'initials',
        'is_verified_user',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getInitialsAttribute(){
        $val = "";

        if(!$this->firstname && !$this->lastname)
          return $this->getDisplaynameAttribute()[0];

        if($this->firstname)
          $val = $val . $this->firstname[0];

        if($this->lastname)
          $val = $val . $this->lastname[0];

        return $val;
    }

    public function getDisplaynameAttribute(){
        $val = "";

        if($this->firstname)
          $val = $val . $this->firstname;

        if($this->lastname)
          $val = $val . $this->lastname;

        if(strlen($val))
          return $val;

        return $this->username;
    }

    public function getChatIDAttribute(){
        return $value = env('APP_ENV', 'local') . '__USER_' . $this->id;
    }


    public function getProfileAttribute(){
        return $this->profileSettings && !empty($this->profileSettings->value) ? $this->profileSettings->value : [

        ];
    }

    public function getSettingsAttribute(){
        return $this->settingSettings && !empty($this->settingSettings->value) ? $this->settingSettings->value : [

        ];
    }

    public function getIsVerifiedUserAttribute($val){
        if($this->email_verified_at)
          return true;

        return false;
    }

    public function getAvatarAttribute($val){
        if(!$val)
          return null;

        if( env('STORAGE_DISK') == 's3' )
          return env('STORAGE_ROOT') . $val;

        if( env('STORAGE_DISK') == 'public' )
          return env('STORAGE_ROOT') . $val;

        if(env('STORAGE_ROOT'))
          return env('STORAGE_ROOT') . 'storage/' . $val;

        return $val;
    }


    // regulat helpers
    public function saveSettings($type, $key, $s=null){
        $this->{$type}()->updateOrCreate(
          [ 'key'     => $key ],
          [
            'uid'     => Str::random(),
            'value'   => $s,
          ]
      );
    }


    // Core Relations
    public function profileSettings(){
        return $this->morphOne(Settings::class, 'setting')->where('key', 'profile.profile');
    }

    public function settingSettings(){
        return $this->morphOne(Settings::class, 'setting')->where('key', 'profile.settings');
    }


    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
            ->orderByRaw("case when read_at IS NULL then 0 else 1 end")
            // ->orderBy('created_at', 'desc')
          ;
    }

    public function unreadNotifications(){
        return $this->notifications()->unread();
    }


    // send Email Verification after registration
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function sendPasswordResetNotification($token){
        $this->notify(new PasswordResetNotification($token));
    }
}
