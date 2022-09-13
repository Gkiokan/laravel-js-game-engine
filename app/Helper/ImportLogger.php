<?php 
namespace App\Helper;

use Log;

trait ImportLogger {
    public function log($message){
        if(env('LOG_IMPORT', true))
            Log::channel('import')->info($message);
    }
}