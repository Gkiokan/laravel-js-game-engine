<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid', 'user_id', 'key', 'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    // protected $hidden = [
    //     'id', 'user_id', 'created_at', 'updated_at',
    //     'setting_id', 'setting_type',
    // ];

    public function setting(){
        return $this->morphTo();
    }

    // disabled due the internal usage of casts
    // public function getValueAttribute($var){
    //     // nessesary mutations before loading the value goes here
    //     // if($var === 'null' OR !isset($var)) return json_encode([]);
    //
    //     return $var;
    // }
}
