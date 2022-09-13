<?php

namespace App\Traits;

use App\Models\Settings;

use Str;
use Auth;

trait SaveSettings {

    protected $settingsKey = 'settings.key';
    protected $settingRelation = 'settingsSettings';

    public function setupSettings(){
        $this->appends[] = 'settings';
        $this->hidden[] = $this->settingRelation;
    }

    public function getSettingsAttribute(){
        $relation = $this->settingRelation;

        return $this->{$relation} && !empty($this->{$relation}->value) ? $this->{$relation}->value : [
            // '_created'  => time(),
            // '_model' => $this->{$relation},
            // '_key'   => $this->settingsKey,
        ];
    }

    public function settingsSettings(){
        return $this->morphOne(Settings::class, 'setting')->where('key', $this->settingsKey);
    }


    public function deleteSettings($key=null, $type=null){
        if(!$key)
          $key = $this->settingsKey;

        if(!$type)
          $type = $this->settingRelation;

        $this->{$type}()->delete();
    }


    public function saveSettings($values=[], $key=null, $type=null){
        if(!$key)
          $key = $this->settingsKey;

        if(!$type)
          $type = $this->settingRelation;

        $this->{$type}()->updateOrCreate(
          ['key' => $key],
          [
            'uid'     => Str::random(),
            'value'   => $values,
          ]
        );
    }


}
