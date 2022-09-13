<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileCrypt extends Model
{
    use HasFactory, \App\Traits\PaginatePerPage;

    protected $fillable = [
        'user_id', 'container_id',
        'releasename', 'releasename_patched',
        'status', 'blocked', 'checked', 'patchable', 'patched',
        'entry_id', 'release_id',
    ];

    public function user(){
        return $this->belongsto(SimpleUser::class);
    }

}
