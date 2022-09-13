<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\PaginatePerPage;
use App\Traits\EntryScopes;
use App\Traits\Filter;

class Entry extends Model
{
    use HasFactory, Filter, EntryScopes, PaginatePerPage;

    protected $fillable = [
        'user_id',
        'type', 'sub_type', 'lang', 'tags', 'active', 'downloads', 'views',
        'genre', 'time',
        'title', 'subtitle', 'fulltitle', 'description',                
        'cover', 'fsk', 'rating', 'options', 'trailer',
        'released_at', 'verified_at',
    ];

    protected $casts = [
        'options'       => 'json',
        'genre'         => 'json',
        'tags'          => 'json',
        'lang'          => 'json',
        'released_at'   => 'date',
        'verified_at'   => 'date',
    ];
    

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function attachments(){
        return $this->morphMany(Attachment::class, 'attachments');
    }

    public function source(){   
        return $this->morphOne(IMDB::class, 'source');
    }

    public function releases(){
        return $this->hasMany(Release::class);
    }

    public function scopeReleaseFromUser($q, $user_id=null){
        $q->orWhereHas('releases', function($release) use ($user_id) {
            $release->where('user_id', $user_id);
        });
    }

    public function scopeOrReleaseName($q, $name=null){
        $q->orWhereHas('releases', function($release) use ($name) {
            $release->where('fulltitle', 'like', "%{$name}%");
        });
    }

}
