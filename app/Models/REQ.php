<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class REQ extends Model
{
    use HasFactory;

    protected $fillable = [
        'fulltitle', 'error', 'release_id', 'filled_at',
    ];            

    public function entry(){
        return $this->belongsTo(Entry::class, 'entry_id');
    }

    public function release(){
        return $this->belongsTo(Release::class, 'release_id');
    }

}
