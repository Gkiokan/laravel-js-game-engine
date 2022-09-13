<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\FileCrypt;

use DB;
use Log;

class SyncMyFolderFromFileCrypt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    
    public $chunks = [];
    public $userID;

    public function __construct($chunks=[], $userID)
    {
        $this->chunks = $chunks;
        $this->userID = $userID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // FileCrypt::truncate();

        $list = [];
        foreach($this->chunks as $item):
            $list[] = [
                'user_id'       => $this->userID,
                'container_id'  => $item['id'], //Arr::get($item, 'id'),
                'releasename'   => $item['name'], //Arr::get($item, 'name'),
            ];
        endforeach;

        $collection = collect($list);
        $chunks = $collection->chunk(500);

        foreach($chunks as $chunk):
            dispatch( function() use ($chunk){
                // \Log::info(["chunk import", $chunk->toArray()]);
                try {
                    DB::table('file_crypts')->upsert($chunk->toArray(), ['container_id'], []); // not working?
                }
                catch (\Exception $e){
                    // Log::info("Error in upsert.. : " . $e->getMessage());
                    Log::info("Error in upsert for chunk");
                }

                // DB::table('file_crypts')->insertOrIgnore($chunk->toArray());

                // // lop though each item
                // foreach($chunk->toArray() as $item):
                //     $exists = FileCrypt::where('container_id', $item['container_id'])->exists();
                    
                //     if(!$exists)
                //         FileCrypt::create($item);
                // endforeach;
            });
        endforeach;
    }
}
