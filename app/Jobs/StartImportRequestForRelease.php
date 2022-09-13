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
use App\Http\Controllers\WCX\ReleaseController;

use App\Models\User;
use App\Models\Release;

use Log;

class StartImportRequestForRelease implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, \App\Helper\ImportLogger;

    public $item;
    public $name;
    public $checked;
    public $user;

    public function __construct($name=null, User $user=null)
    {
        // $this->item     = $item;
        // $this->name     = $item->releasename;
        // $this->checked  = $item->checked;
        $this->name     = $name;
        $this->user     = $user;
    }

    public function handle()
    {
        $this->log("---");
        $this->log("Dispatching Job import from FC release $this->name for user {$this->user->username}");

        $fc = new FileCryptImportController;
        $fc->setUser($this->user);

        $request = new Request();
        $request->replace([
            'id'   => null,
            'name' => $this->name,
        ]);        
        // $fc->importReleaseAndDispatch($request);

        $container      = null;
        $releaseName    = $this->name;

        $this->log("Import Release and Dispatch Start Release import only $releaseName");

        $item = $fc->createBasicItem();
        $item['user_id']        = $this->user->id;
        $item['container_id']   = $container;
        $item['fulltitle']      = $releaseName;

        // pre patch
        $item['type']           = $fc->findType($releaseName);
        $item['title']          = $fc->getCleanName($item);
        $item['group']          = $fc->getGroup($item);

        // patch source 
        $item = $fc->patchSourceValue($item);                

        // check if have a release from the user with the releasename and no entry_id
        // if($release = Release::where('user_id', $this->user->id)->where('fulltitle', $releaseName)->whereNull('entry_id')->first() ):
        if($release = Release::where('user_id', $this->user->id)->where('fulltitle', $releaseName)->first() ):
            $this->log("Import Release seems to exist on Release ID {$release->id}. Dispatching next Job.");                                    
            $item['release_id'] = $release->id;            
            
            $this->dispatchNextJob($item);        
            return;
        endif;        

        // patch with FC
        $item = $fc->patchWithFileCrypt($request, $item);

        if( count($item['crypted_links']) == 0):
            $fc->updateConnectedFileCryptContainer($item, [
                'checked'    => true,
                'patchable'  => false,
                'patched'    => false,
                'status'     => 'no_container_found',
                'release_id' => $release->id,
            ]);            
            $this->log("Import Release and Dispatch failed: No crypted links found for $releaseName. Leaving Job.");
            return;
        endif;        

        // fill the release 
        $fillRequest = (new \Illuminate\Http\Request())->replace($item);
        $release     = (new ReleaseController)->create($fillRequest, true);

        $fc->updateConnectedFileCryptContainer($item, [
            'checked'    => true,
            'patchable'  => false,
            'patched'    => false,
            'status'     => 'waiting_for_next_job',
            'release_id' => $release->id,
        ]);
        
        $item['release_id'] = $release->id;
        $this->log("Import Release success. Release ID {$release->id}. Dispatching next Job.");
        
        // dispatch next job
        $this->dispatchNextJob($item);

        /**
         * The flow of importing releases though Release Name
         * 1.) StartImportRequestForRelease
         * 2.) ImportReleaseXREL / ImportReleaseIGDB / ImportReleaseIMDB
         * 3.) ImportReleaseEntryDataPatch
         * 4.) ImportReleaseCompleteCreateEntries
         */
    }

    public function dispatchNextJob($item=[]){        
        $fc = new FileCryptImportController;

        // check if we have clean name and jump direclty to IMDB
        // if( in_array($item['type'], ['movie', 'series', 'anime', 'doku']) ):
        if( $fc->isMovie($item) ):
            $this->log("Import found type {$item['type']}. Dispatch ImportReleaseIMDB");
            dispatch( new ImportReleaseIMDB($item) );

        elseif( $fc->isGame($item) ):
            $this->log("Import found type {$item['type']}. Dispatch ImportReleaseIGDB");
            dispatch( new ImportReleaseIGDB($item) );

        else:
            // fallback to xREL
            $this->log("Import found type {$item['type']}. Fallback dispatch ImportReleaseXREL");
            dispatch( new ImportReleaseXREL($item) );        

        endif;        
    }

}
