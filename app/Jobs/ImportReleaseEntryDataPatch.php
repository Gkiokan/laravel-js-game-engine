<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Http\Controllers\WCX\FileCryptImportController;
use App\Http\Controllers\WCX\Patcher\MoviePatcher;

use Arr;

use function PHPUnit\Framework\returnValue;

class ImportReleaseEntryDataPatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, \App\Helper\ImportLogger;

    public $tries   = 10;
    public $backoff = 30;
    public $maxExceptions = 5;
    
    public $item;

    public function __construct($item=[])
    {
        $this->onQueue('import_patch');
        $this->item = $item;
    }

    public function handle()
    {
        $fc = new FileCryptImportController;

        $item = $this->item;

        $this->log("Import Release Entry Data and Patch {$item['fulltitle']}");

        // patch with IMDB
        // if( in_array($item['type'], ['movie', 'series', 'anime', 'doku']) ):   
        if( $fc->isMovie($item) ):     
            if($item['imdb_id'] == null):            
                $fc->updateConnectedFileCryptRelease($item, [
                    'patchable' => false,
                    'status'    => 'missing_imdb_id',
                ]);
                
                $this->log("Import STOPPED missing imdb_id. \n\n");
                return;
                // throw new \App\Exceptions\ImportException("Missing IMDB ID for Release ${itme['fulltitle']}");
            endif;        

            $fc->updateConnectedFileCryptRelease($item, [
                'patchable' => true,
                'status'    => 'waiting_for_imdb',
            ]);
            
            $this->log("Import found IMDB ID {$item['imdb_id']}");

            // 
            // Patch Data here
            //            
            $item = $fc->patchWithIMDB($item);

            // Try to double patch 
            // try {
            // $item = $fc->patchWithIMDB($item);
            // }
            // catch( \Exception $e){
            //     $this->log("Import Patch withWithIMDB Error " . $e->getMessage());
            //     $this->log("Import fallback to search imdb items array");

            //     if( $imdb_items = Arr::get($item, 'imdb_search.results', false) ):
            //         $this->log("Import FB found imdb_search items. Looking for {$item['imdb_id']}");
            //         foreach($imdb_items as $imdb):
            //             if( Arr::get($imdb, 'id', false) == $item['imdb_id']):
            //                 $this->log("Import Found {$item['imdb_id']} in IMDB Search. Gonna patch it");
            //                 $foundIMDB = $imdb;

            //             endif;
            //         endforeach;
            //     endif;
            // }

        // patch with IGDB
        elseif( $fc->isGame($item) ):
            $fc->updateConnectedFileCryptRelease($item, [
                'patchable' => true,
                'status'    => 'waiting_for_igdb',
            ]);            

            // 
            // Patch Data here            
            //
            $item = $fc->patchWithIGDB($item);

            // post check if we got a igdb object to clarify patch
            if(!$item['igdb']):
                $fc->updateConnectedFileCryptRelease($item, [
                    'patchable' => true,
                    'status'    => 'missing_igdb',
                ]);            

                $this->log("Import STOPPED. IGDB Object not found.");

                return;
            endif;

        else:
            $fc->updateConnectedFileCryptRelease($item, [
                'patchable' => true,
                'status'    => 'no_type_patcher',
            ]);

            $this->log("Import STOPPING because no patcher does exist for type {$item['type']}");
            return;
            // throw new \App\Exceptions\ImportException("Import STOPPING because no patcher does exist for type {$item['type']}");
        endif;

        dispatch( new ImportReleaseCompleteCreateEntries($item) );
    }


    public function tags(){
        return [
            'import_patch:' . $this->item['fulltitle'],
            'type:' . $this->item['type'],
        ];
    }

}
