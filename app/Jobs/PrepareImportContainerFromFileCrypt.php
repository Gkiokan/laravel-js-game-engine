<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Http\Request;
use App\Http\Controllers\WCX\FileCryptImportController;

use App\Models\FileCrypt;
use App\Models\User;

use DB;
use Log;

class PrepareImportContainerFromFileCrypt implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, \App\Helper\ImportLogger;

    public $timeout = 120;
    
    public $items = [];
    public $user;
    public $chunkSize = 3;

    public function __construct($items=[], User $user=null, $chunkSize=3)
    {
        $this->items = $items;
        $this->user  = $user;
        $this->chunkSize = $chunkSize;
    }

    public function handle()
    {
        $this->log("Running ImportContainerFromFileCrypt and generating StartImportRequestForRelease Jobs");
        $collection = collect($this->items);
        $count      = $collection->count();
        $chunks = $collection->chunk($this->chunkSize);     
        $user   = $this->user;

        Log::info("Loop though {$count} and generated " . count($chunks) . " chunks with " . $this->chunkSize . " items per chunk and dispatch jobs for them.");

        foreach($chunks as $key => $chunk):
            $items = $chunk->toArray();              

            dispatch( function() use ($key, $items, $user){
                Log::info("Generating Jobs for chunk $key");
                foreach($items as $item):
                    dispatch( new StartImportRequestForRelease($item->releasename, $user));                    
                endforeach;
            });
        endforeach;
    }

}
