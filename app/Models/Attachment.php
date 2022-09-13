<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'belongsTo', 'file', 'uid',
        'name', 'slug', 'size', 'mime', 'ext', 'path', 'thumbnail', 'type', 'cat',
        'version', 'file'
    ];


    public function getPathAttribute($val){
        return $this->prefixAttachmentRoot($val);
    }

    public function getThumbnailAttribute($val){
        if($val)
          return $this->prefixAttachmentRoot($val);

        return $val;
    }

    public function getSizeString(){
        $bytes = $this->size;

        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }


    public function prefixAttachmentRoot($val){
        if( env('STORAGE_DISK') == 's3' )
          return env('STORAGE_ROOT') . $val;

        return $val;
    }

    // relations
    public function pkg(){
        return $this->morphTo(__FUNCTION__, 'attachments_type', 'attachments_id');
    }

    public function attachment(){
        return $this->morphTo();
    }

    public function downloads(){
        return $this->hasMany(Download::class);
    }

}
