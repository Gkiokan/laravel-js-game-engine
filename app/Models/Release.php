<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\PaginatePerPage;

use Arr;

class Release extends Model
{
    use HasFactory, PaginatePerPage;

    protected $fillable = [
        'user_id', 'title', 'fulltitle', 'type', 
        'links', 'crypted_links',
        'size', 'parts', 'group', 'options', 'downloads', 'source',
        'quality', 'video_stream', 'video_codec', 'audio_stream', 'bitrate', 'lang',
    ];

    protected $casts = [
        'links' => 'array',
        'crypted_links' => 'array',
        'lang'          => 'array',
        'options'       => 'json',
    ];

    protected $touches = ['entry'];


    public function entry(){
        return $this->belongsTo(Entry::class);
    }

    public function user(){
        return $this->belongsTo(SimpleUser::class, 'user_id');
    }

    public function simpleUser(){
        return $this->belongsTo(SimpleUser::class, 'user_id');
    }


    public function getOptionsAttribute($json){
        $items = json_decode($json, true);

        if($this->type == 'series'):
            $check = ['season', 'episode', 'episode_count_in_season'];

            foreach($check as $i):
                if(!Arr::get($items, $i, false)):
                    $items[$i] = null;
                endif;
            endforeach;     
        endif;

        return $items;
    }

}
