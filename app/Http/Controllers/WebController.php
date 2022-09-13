<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use File;
use App\Models\Config;

class WebController extends Controller
{

    public function get($root='app', $any='index.html'){                
        $path = public_path() . '/' . $root . '/' . $any;

        if(!file_exists($path))
          // return response('Nothing here. ' . $path, 404);
          $path = public_path() . '/app/index.html';
        
        if(!file_exists($path))
          return view('maintenance');


        $file = File::get($path);
        $mime = File::mimeType($path);

        return response($file, 200, ['Content-Type' => $mime]);
        // return File::get($path);
    }

    public function getFile(Request $r, $path){
        $path = public_path() . '/' . $path;

        if(!file_exists($path))
          return abort(404);

        $file = File::get($path);
        $mime = File::mimeType($path);

        return response($file, 200, ['Content-Type' => $mime]);
    }


    public function app(Request $r, $any='index.html'){
        return $this->get('app', $any);
    }


    // Return assets
    public function assets($assets){
      $path = public_path('/modules/' . $assets);

      dd($path);

      if(!File::exists($path)) dd($path);

      $file = File::get($path);
      $mime = File::mimeType($path);

      $response = Response::make($file, 200);
      $response->header("Content-Type", $mime);

      return $response;
    }

    // sw
    public function sw(){
      $path = public_path('app/service-worker.js');

      $file = File::get($path);
      $mime = File::mimeType($path);

      header('Content-type: text/javascript');
      header('Service-Worker-Allowed: /');
      die($file);

      $response = Response::make($file, 200);
      $response->header("Content-Type", $mime);

      return $response;
    }

    // wp-admin
    public function wplogin(){
      return view('wp.wp-admin');
    }

    // public function wpIncludes($path=""){

    // }


    // Return Sitemap
    // public function sitemap()
    // {
    //   $path = public_path('modules/trx/sitemap.xml');
    //   $map  = SitemapGenerator::create('https://tronconomy.de')->getSitemap();
    //
    //   $map->add(Url::create('home'));
    //   $map->add(Url::create('about'));
    //   $map->add(Url::create('login'));
    //   $map->add(Url::create('privacy'));
    //   $map->add(Url::create('terms-of-service'));
    //
    //   $map->writeToFile($path);
    //
    //   // dump print
    //   header('Content-type: text/xml');
    //   die(File::get($path));
    // }


}
