<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attachment;
use App\Models\User;

// use App\Jobs\CreateThumbnail;

use File;
use Auth;
use Storage;
use Str;
use Image;

class FileController extends Controller
{

    public function upload($pkg, $file, $type='file', $cat='upload'){
        return $this->save_file($pkg->id, strtoupper($pkg->cusa), $file, $type, $cat, false);
    }

    public function copy($pkg, $file, $type='file', $cat='upload', $version="0.00", $name=""){
        return $this->save_file_from_copied_url($pkg->id, strtoupper($pkg->cusa), $file, $type, $cat, false, $version, $name);
    }


    public function uploadFiles(Request $r, $return=null, $prefixFolder=null){
        $x = [];
        $files = $r->file('files');
        $id    = $r->input('id');
        $uid   = $t->uid;
        $cat   = $r->input('cat');
        $dir   = $prefixFolder ? $prefixFolder : date('Y/m/d') . '/' . $id; // Str::random(5);
        $type  = 'image';

        if( is_array($files) )
            foreach($files as $key => $file):
                $y = $this->save_file($id, $dir, $file, $type, $cat, true);
                if($y) $x[] = $y;
            endforeach;
        else
            $x = [];

        if($return)
        return $x;

        return response()->json([
            'files'     => $x,
            // 'files' => count($files)
        ]);
    }


    public function save_file($belongsTo=null, $dir='', $file=null, $type='file', $cat='', $obscure=false){
          if(!$file) return;

          $name = $file->getClientOriginalName();
          $path = $file->getRealPath();
          $size = $file->getSize();
          $mime = $file->getMimeType();
          $ext  = $file->getClientOriginalExtension();
          $user_id = Auth::user() ? Auth::user()->id : 0;

          $filename = pathinfo($name, PATHINFO_FILENAME);
          // $extension = pathinfo($name, PATHINFO_EXTENSION);

          // pre configs
          $base   = 'attachments/' . $dir;
          $pre    = Str::slug($filename); //date('Y-m-d') . '_' . time() . '_' . Str::slug($filename);

          // patch for covers
          if($cat == 'cover')
            $pre = $dir . '_' . $pre;

          $slug   = $pre . '.' . $ext;
          $thumb  = $pre . '_thumbnail.' . $ext;

          // hide the filename, hash and map it
          if( $obscure ):
            $slug = Str::random(16);
            $thumb = $slug . '_thumbnail';
          endif;

          // save the file
          // Storage::disk('public')->putFileAs($base, $file, $slug);

          // https://stackoverflow.com/questions/48783020/laravel-s3-image-upload-creates-a-folder-with-the-filename-automatically
          // $storage_path = Storage::disk('s3')->put($base.'/'.$slug, $file, 'public');
          $storage_path = Storage::disk('private')->putFileAs($base, $file, $slug);

          $a = Attachment::create([
              'belongsTo' => $belongsTo,
              'user_id'   => $user_id,
              'uid'       => Str::random(12),
              'name'  => $name,
              'slug'  => $slug,
              'size'  => $size,
              'mime'  => $mime,
              'ext'   => $ext,
              'path'  => $storage_path,
              'thumbnail' => null, // $storage_path,
              // 'path'  => $base . '/' . $slug,
              // 'thumbnail' => $base . '/' . $slug,
              'type'  => $type,
              'cat'   => $cat,
              // 'file'  => $base . '/' . $slug,
              // 'data'  => $c,
          ]);

          // CreateThumbnail::dispatch($a, $base, $thumb);

          return $a; // compact('name', 'slug', 'size', 'path'); // $a;
    }


    public function save_file_from_copied_url($belongsTo=null, $dir='', $file=null, $type='file', $cat='', $obscure=false, $version, $name){
          if(!$file) return;

          $name = pathinfo($name, PATHINFO_FILENAME); // $file->getClientOriginalName();
          // $path = $file->getRealPath();
          $size = strlen($file); // $file->getSize();
          $mime = "application/octet-stream"; // $file->getMimeType();
          $ext  = "pkg"; // $file->getClientOriginalExtension();
          $user_id = Auth::user() ? Auth::user()->id : User::first()->id;

          $filename = pathinfo($name, PATHINFO_FILENAME);
          // $extension = pathinfo($name, PATHINFO_EXTENSION);

          // pre configs
          $base   = 'attachments/' . $dir;
          $pre    = Str::slug($filename . "-v{$version}"); //date('Y-m-d') . '_' . time() . '_' . Str::slug($filename);
          $slug   = $pre . '.' . $ext;
          $thumb  = $pre . '_thumbnail.' . $ext;

          // hide the filename, hash and map it
          if( $obscure ):
            $slug = Str::random(16);
            $thumb = $slug . '_thumbnail';
          endif;

          // save the file
          // Storage::disk('public')->putFileAs($base, $file, $slug);

          // https://stackoverflow.com/questions/48783020/laravel-s3-image-upload-creates-a-folder-with-the-filename-automatically
          $storage_path = Storage::disk('private')->put($base.'/'.$slug, $file, 'public');
          $storage_path = $base.'/'.$slug;
          // $storage_path = Storage::disk('private')->putFileAs($base, $file, $slug);

          $a = Attachment::create([
              'belongsTo' => $belongsTo,
              'user_id'   => $user_id,
              'uid'       => Str::random(12),
              'name'  => $name,
              'slug'  => $slug,
              'size'  => $size,
              'mime'  => $mime,
              'ext'   => $ext,
              'path'  => $storage_path,
              'thumbnail' => null, // $storage_path,
              // 'path'  => $base . '/' . $slug,
              // 'thumbnail' => $base . '/' . $slug,
              'type'  => $type,
              'cat'   => $cat,
              // 'file'  => $base . '/' . $slug,
              // 'data'  => $c,
          ]);

          // CreateThumbnail::dispatch($a, $base, $thumb);

          return $a; // compact('name', 'slug', 'size', 'path'); // $a;
    }

    public function download($id=null){
        if(!$id) return response()->json([
            'message' => "No ID."
        ], 402);

        $file = Attachment::find($id);

        if(!$id) return response()->json([
            'message' => "No Attachment"
        ], 404);

        return response()->download(
            storage_path('app/' . $file->path),
            $file->name,
            [ 'Content-Type' => $file->mime ]
        );

        $file->content = base64_encode(Storage::get($file->path));

        return response()->json([
            'file'  => $file
        ]);
    }
}
