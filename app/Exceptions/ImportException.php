<?php

namespace App\Exceptions;

use Exception;

class ImportException extends Exception
{
    public $error;

    public function __construct($error=''){
        $this->error = $error;
    }

    public function report(){
        \Log::info("import STOPPED. $this->error");
        return false;
    }

    public function render(){
        return false;
        return response()->json([
            'message'       => "Import STOPPED. $this->error",
        ], 409);
    }
}
