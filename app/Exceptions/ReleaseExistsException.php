<?php

namespace App\Exceptions;

use Exception;
use App\Models\Release;

class ReleaseExistsException extends Exception
{
    
    public function __construct(Release $release){
        $this->release = $release;
    }
    
    public function report(){
        return false;
    }

    public function render(){
        return response()->json([
            'release_name'  => $this->release->fulltitle,
            'message'       => "Release " . $this->release->fulltitle . " already exists",
        ], 409);
    }    
}
