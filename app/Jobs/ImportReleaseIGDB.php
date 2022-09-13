<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Http\Controllers\WCX\FileCryptImportController;

class ImportReleaseIGDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, \App\Helper\ImportLogger;

    public $tries   = 10;
    public $backoff = 30;
    public $maxExceptions = 10;
    
    public $item;

    public function __construct($item=[])
    {
        $this->onQueue('import_release');
        $this->item = $item;
    }

    
    public function handle()
    {
        $fc = new FileCryptImportController;   
        
        $item = $this->item;
        $name = $item['title'];
        $this->log("Import Release IGDB Patch data by clean name {$this->item['title']}");

        if($name == 'Indie-Spiele'):
            $fc->updateConnectedFileCryptRelease($item, [
                'checked'   => true,
                'patchable' => false,
                'status'    => 'wrong_name ' . $name,
            ]);    
            return;            
        endif;

        $item = $fc->searchForPatchableData($item);

        if($item['igdb']):
            $this->log("Import Release Found IGDB Object. Dispatching next Job.");

            dispatch( new ImportReleaseEntryDataPatch($item) );            
        else:
            $fc->updateConnectedFileCryptRelease($item, [
                'checked'   => true,
                'patchable' => false,
                'status'    => 'igdb_not_found_fb_to_xrel',
            ]);    

            dispatch( new ImportReleaseXREL($item) );
        endif;
    }

    public function tags(){
        return [
            'import_release:' . $this->item['fulltitle'],
            'type:' . $this->item['type'],
        ];
    }    

}
