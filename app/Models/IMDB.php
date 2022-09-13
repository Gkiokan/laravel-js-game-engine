<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMDB extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'url', 'source', 'source_id', 'lang',
        'key', 'value',
    ];

    protected $hidden = [
        'user_id', 'url',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public function source(){
        return $this->morphTo();
    }

}
