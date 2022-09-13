<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

    protected $fillable = ['key', 'value'];

    public function scopeGetEmailConfiguration($q){
        $q->whereIn('key', ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_name', 'mail_from_address']);
    }

    public function scopeKey($q, $key){
        $q->where('key', $key);
    }

    public function scopeValue($q, $key){
        $item = $q->where('key', $key)->first();
        return $item ? $item->value : null;
    }

    public function fromConfig($key, $fb='NOT_SET'){
        $val = $this->firstWhere('key', $key);
        return $val ? $val->value : $fb;
    }

    public function getFooterLinks(){
        $item = Config::where('key', 'footer_links')->first();

        if(!$item)
            $items = [];
        else 
            $items = json_decode($item->value, true);
    
        return $items;
    }

    public function getThirdPartyLinks(){
        $item = Config::where('key', 'footer_third_party')->first();

        if(!$item)
            $items = [
                'telegram'  => [ 'name' => 'telegram', 'link' => '', 'icon' => 'fab fa-telegram' ],
                'discord'   => [ 'name' => 'discord', 'link' => '', 'icon' => 'fab fa-discord' ],
                'instagram' => [ 'name' => 'instagram', 'link' => '', 'icon' => 'fab fa-instagram' ],
                'facebook'  => [ 'name' => 'facebook', 'link' => '', 'icon' => 'fab fa-facebook' ],
                'tiktok'    => [ 'name' => 'tiktok', 'link' => '', 'icon' => 'fab fa-tiktok' ],
                'twitter'   => [ 'name' => 'twitter', 'link' => '', 'icon' => 'fab fa-twitter' ],                
            ];
        else 
            $items = json_decode($item->value, true);
    
        return $items;
    }

    public static function getConfig($input=[]){
          $arr = count($input)? $input : Config::all();
          $l = [];
          foreach($arr as $a)
            $l[$a->key] = $a->value;

          $c = collect($l);
          return $c;
    }

}
