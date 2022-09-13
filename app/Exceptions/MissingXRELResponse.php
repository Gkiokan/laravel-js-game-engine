<?php

namespace App\Exceptions;

use Exception;

class MissingXRELResponse extends Exception
{
    public $name;

    public function __construct($name=''){
        $this->name = $name;
    }

    public function report(){
        return false;
    }

    public function render(){
        return response()->json([
            'message'       => "Import stopped. Missing xREL response for Release $this->name",
        ], 422);
    }    
}
