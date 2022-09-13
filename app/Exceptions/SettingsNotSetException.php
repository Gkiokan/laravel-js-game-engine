<?php

namespace App\Exceptions;

use Exception;

class SettingsNotSetException extends Exception
{
    public $message;

    public function __construct($message=""){
        $this->message = $message;
    }

    public function report(){
        return false;
    }    

    public function render(){
        return response()->json([            
            'message' => $this->message,
        ], 422);        
    }
}
