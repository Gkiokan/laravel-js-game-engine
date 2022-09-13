<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attachment;
use App\Models\Download;
use App\Models\CoverDownload;
use App\Models\PKG;

use Str;
use File;
use Log;
use Auth;
use Storage;
use Response;

class AttachmentController extends Controller
{

    public function get(Request $r, $asset=''){

        $org = $asset;
        $asset = filter_var($asset,FILTER_SANITIZE_STRING);

        $path = 'attachments/' . $asset;
        $lookup = 'attachments/' . $org;

        if(!Storage::disk('private')->exists($path)) return abort(404, "Unknown attachment requested $asset"); // abortresponse("#404 | " . $path, 404);

        // simple disk download
        // return Storage::disk('private')->download($path);
        // Log::info("Attachment:: Download Request for $lookup");

        // find attachment
        $item = Attachment::where('path', $lookup)->first();

        if(!$item):
          Log::info("Attachment:: Download Request for $lookup not found");
          return response()->json(['message' => "Download Request $path not found."], 404);
        endif;

        // patch full path
        $path = storage_path() . '/app/private/' . $path;

        // ??
        // $file = File::get($path);
        // $mime = File::mimeType($path);

        header('Content-Disposition: attachment; filename="' . $item->name . '"');
        header("Content-Length: " . filesize($path));
        return response()->file($path);//
    }

    public function serve($path=null, $name='PKG_ZONE_PKG_DOWNLOAD.pkg'){
        // patch full path
        $path = storage_path() . '/app/private/' . $path;

        // response
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header("Content-Length: " . filesize($path));
        return response()->file($path);
    }


    public function stream(Request $r, $asset=''){
        $asset = str_replace('stream/', '', $asset);
        $org = $asset;
        $asset = filter_var($asset,FILTER_SANITIZE_STRING);

        $path = 'attachments/' . $asset;
        $lookup = 'attachments/' . $org;

        if(!Storage::disk('private')->exists($path)) return abort(404, "Unknown attachment requested $asset"); // abortresponse("#404 | " . $path, 404);

        // simple disk download
        // return Storage::disk('private')->download($path);
        // Log::info("Attachment:: Download Request for $lookup");

        // find attachment
        $item = Attachment::where('path', $lookup)->first();

        if(!$item):
          Log::info("Attachment:: Download Request for $lookup not found");
          return response()->json(['message' => "Download Request $path not found."], 404);
        endif;

        // patch full path
        $path = storage_path() . '/app/private/' . $path;

        Log::info($r);

        // response
        return $this->get($path);
    }


}
