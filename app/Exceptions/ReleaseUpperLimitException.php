<?php

namespace App\Exceptions;

use Exception;

class ReleaseUpperLimitException extends Exception
{
    public $count = 0;

    public function __construct($count=0){
        $this->count = $count;
    }
    
    public function report(){
        return false;
    }

    public function render(){
        return response()->json([            
            'error' => "Release has already {$this->count} upper. Max upper Limit exceed.",
        ], 409);
    }       
}
