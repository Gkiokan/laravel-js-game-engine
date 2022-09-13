<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Http\Controllers\WCX\EntryController;
use App\Http\Controllers\WCX\ReleaseController;
use App\Http\Controllers\WCX\FileCryptImportController;

use App\Events\NewEntryAdded;
use App\Models\Release;

class ImportReleaseCompleteCreateEntries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, \App\Helper\ImportLogger;

    public $tries   = 15;
    public $backoff = 30;
    public $maxExceptions = 20;

    public $item;

    public function __construct($item=[])
    {
        $this->onQueue('import_complete');
        $this->item = $item;
    }

    public function handle()
    {
        $this->log("Import Create Entries Job");

        $item = $this->item;

        $this->log("Import running patch complete and create entry for {$item['fulltitle']}");

        $patched = $item['patched'];             
        $fc = new FileCryptImportController;

        if($patched):
            $this->log("Import Item is patched and ready to be added");

            $fillRequest = (new \Illuminate\Http\Request())->replace($item);
            $entry       = (new EntryController)->create($fillRequest, true);
            $release     = Release::where('id', $item['release_id'])->firstOrFail();

            // save release and reload entry
            $entry->releases()->save($release);
            $this->log("Import Item has been mapped to Entry ID {$entry->id}");

            // fill My FileCrypt             
            // $updatedFCC = $fc->updateConnectedFileCryptContainer($item, [
            $updatedFCC = $fc->updateConnectedFileCryptRelease($item, [
                'checked'   => true,
                'patchable' => true,
                'patched'   => true,
                'status'    => 'imported',
                'release_id'    => $release->id,
                'entry_id'      => $entry->id,
            ]);
            $this->log("Import Item has been updated. Release ID {$release->id}. Entry ID {$entry->id}. ");

            // fill REQ 
            (new ReleaseController)->fillREQ($release, $entry);

            $this->log("Import check if fillREQ is available");

            // notify telegram bot channel
            if(env('SEND_TELEGRAM', false)):
                $this->log("Import call Event NewEntryAdded");
                event(new NewEntryAdded($entry, $release));            
            endif;
            
            $this->log("Import COMPLETE {$item['fulltitle']}");
            \Log::info("Import COMPLETE {$item['fulltitle']}");
        else:
            $this->log("Import Item is not fully patched. Aboarding. \n");

            $updatedFCC = $fc->updateConnectedFileCryptContainer($item, [
                'checked'   => true,
                'patchable' => true,
                'patched'   => false,
                'status'    => 'not_patched',
            ]);            
        endif;        
    }

    public function tags(){
        return [
            'import_complete:' . $this->item['fulltitle'],
            'title:' . $this->item['title'],
            'type:' . $this->item['type'],
        ];
    }    

}
