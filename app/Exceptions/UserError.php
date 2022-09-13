<?php

namespace App\Exceptions;

use Exception;

class UserError extends Exception
{
    public $val;

    public function __construct($val, $code=404){
        $this->val  = $val;
        $this->code = $code;
    }

    public function render(){
        return response()->json([
            'message' => $this->val,
            'value' => 'You have errors with your account',
        ], $this->code);
    }
}
