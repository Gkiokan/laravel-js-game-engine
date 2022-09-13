<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Http\Controllers\WCX\FileCryptImportController;

class ImportReleaseIMDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, \App\Helper\ImportLogger;

    public $tries   = 10;
    public $backoff = 30;
    public $maxExceptions = 5;

    public $item;

    public function __construct($item=[])
    {
        $this->onQueue('import_release');
        $this->item = $item;
    }

    public function handle()
    {
        
        $fc = new FileCryptImportController;
        
        $this->log("Import Release IGDB Patch data by clean name {$this->item['title']}");
        $item = $fc->searchForPatchableData($this->item);

        if($item['imdb_id']):
            $this->log("Import Release search found title {$item['title']} and IMDB ID {$item['imdb_id']}");
        else:
            $fc->updateConnectedFileCryptRelease($item, [
                'checked'   => true,
                'patchable' => false,
                'status'    => 'missing_imdb_id',
            ]);    
            $this->log("Import Release search for {$item['title']} but no IMDB_ID found! Fallback to xREL");
            // \Log::info($item['imdb_search']);

            dispatch( new ImportReleaseXREL($item) );        
            return;            
        endif;

        $fc->updateConnectedFileCryptRelease($item, [
            'checked'   => true,
            'patchable' => true,
            'status'    => 'waiting_for_insert',
        ]);                

        $this->log("IMDB seems to be found and patched. Dispatching next Job.");
        dispatch( new ImportReleaseEntryDataPatch($item) );
        
    }

    public function tags(){
        return [
            'import_release:' . $this->item['fulltitle'],
            'type:' . $this->item['type'],
        ];
    }    
}
