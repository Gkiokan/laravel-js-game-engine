<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Config;

use Cache;

class AdminController extends Controller
{

    public function getApplicationConfig(Request $r){
        // $item         = Config::where('key', 'footer_links')->first();
        // $footerLinks  = !$item ? [] : json_decode($item->value, true);

        $config = Cache::remember('app.get.config', 60 * 24 * 7 * 4, function(){
            $config = new Config;
            $footerLinks        = $config->getFooterLinks();
            $footerText         = nl2br(strip_tags($config->fromConfig('footer_text', '')));
            $footerThirdParty   = $config->getThirdPartyLinks();

            return [
                'footerLinks'   => $footerLinks,
                'footerText'    => nl2br(strip_tags($footerText)),
                'footerThirdParty' => $footerThirdParty,
            ];
        });

        return response()->json($config);        
    }
    
    public function getFooter(Request $r){
        // $items = (new Config)->fromConfig('footer_links', []);

        $config = new Config;
        
        $footerLinks        = $config->getFooterLinks();
        $footerText         = $config->fromConfig('footer_text', '');
        $footerThirdParty   = $config->getThirdPartyLinks();

        return response()->json([
            'footerLinks' => $footerLinks,
            'footerText'  => $footerText,
            'footerThirdParty' => $footerThirdParty,
        ]);
    }

    public function saveFooter(Request $r){
        // $item = Config::where('key', 'footer_links')->first();
        
        // save footer links
        $item = Config::key('footer_links')->first();     
        $value = $footerLinks = json_encode($r->items);        
        if(!$item)
            $item = Config::create(['key' => 'footer_links', 'value' => $value ]);
        else 
            $item->update(['value' => $value ]);


        // save footer third party
        $item = Config::key('footer_third_party')->first();     
        $value = $footerLinks = json_encode($r->footerThirdParty);        
        if(!$item)
            $item = Config::create(['key' => 'footer_third_party', 'value' => $value ]);
        else 
            $item->update(['value' => $value ]);
            
        
        // save footer text
        $item = Config::key('footer_text')->first();
        $value = $footerText = $r->footerText;
        if(!$item)
            $item = Config::create(['key' => 'footer_text', 'value' => $value ]);
        else 
            $item->update(['value' => $value ]);
        
        
        // response
        return response()->json([
            'item'  => $item,
            'message'     => 'saved',
            'footerLinks' => json_decode($footerLinks),
            'footerText'  => $footerText,
        ]);
    }

}
