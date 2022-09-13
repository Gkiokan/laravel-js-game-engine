<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\RateLimited;

use App\Http\Controllers\WCX\FileCryptImportController;

class ImportReleaseXREL implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, \App\Helper\ImportLogger;

    public $tries   = 10;
    public $backoff = 30;
    public $maxExceptions = 10;

    public $item;

    public function __construct($item=[])
    {
        $this->onQueue('import_release_xrel');
        $this->item = $item;
    }

    public function middleware(){
        return [
            new RateLimited('xrel'),
        ];
    }

    public function handle()
    {
        $this->log("Import Dispatching xREL Patch Job");

        $fc = new FileCryptImportController;

        // search for release with xrel
        $item = $fc->getXREL($this->item);        

        // stop here if we can't continue
        if($item['xrel'] == null):            
            $error = 'missing_xrel';

            if($item['xrel_error']):
                $error = 'xrel_error_' . $item['xrel_error'];
                $this->log("Import xREL Error: {$item['xrel_error']}");
            endif;

            $fc->updateConnectedFileCryptRelease($item, [
                'checked'   => true,
                'patchable' => false,
                'status'    => $error,
            ]);
            
            $this->log("Import STOPPED missing xrel response. {$item['fulltitle']} \n\n");            
            throw new \App\Exceptions\MissingXRELResponse($item['fulltitle']);
        endif;

        $fc->updateConnectedFileCryptRelease($item, [
            'checked'   => true,
            'patchable' => true,
            'status'    => 'waiting_for_patcher',
        ]);        

        $this->log("Import xREL seems to be added. Doing next Job.");
        dispatch( new ImportReleaseEntryDataPatch($item) );
    }

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function backoff(){
        return [10,30,60];
    }

    public function tags(){
        return [
            'import_release_xrel:' . $this->item['fulltitle'],
            'type:' . $this->item['type'],
        ];
    }    
}
