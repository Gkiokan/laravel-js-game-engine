<?php

namespace App\Exceptions;

use Exception;

class ReleaseBelongsNotToYou extends Exception
{

    
    public function report(){
        return false;
    }

    public function render(){
        return response()->json([
            'message'       => "Release does not belong to you. Cancel.",
        ], 200);
    }    
}
