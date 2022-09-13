<?php

namespace App\Http\Controllers;

use App\Http\Controllers\WCX\IRCClient;
use Illuminate\Http\Request;


use App\Models\Release;
use App\Models\REQ;

class IRCController extends Controller
{

    // public $channel = "#NGPre.SPAM";
    public $channel = "#ngpre.test";
    public $nick    = "TestBot1";

    static $user = "request";
    static $pass = "azw9fuzdwfxa";
    static $host = "62.171.139.183";
    static $port = "31569";
    
    public $client  = null;


    public function request(Request $r){
        $exist = Release::where('fulltitle', $r->title)->first(); 

        if($exist)
            return response()->json([
                'message'   => "release.release_exists",
                'release'   => $exist,
            ]);

        $item = REQ::where('fulltitle', $r->title)->first();

        if($item)
            return response()->json([
                'message'   => "release.request_exists",
            ]);

        return $this->push($r);        
    }

    
    public function push(Request $request){
        $m = [];
        $r = [];
        $limit = 5;
        $i = 0;
        $do = 1;

        $client = $this->client = new IRCClient(self::$user, self::$pass);


        $client->connect();
        $r[] = $client->read(2048);        

        // sleep(1);
        $client->joinChannel($this->channel);
        // $client->joinChannel('/');
        // $client->send("/join {$this->channel}");
        
        $r[] = $ping = $client->read(16);
        $r[] = $this->isPing($ping);
        $r[] = $client->read();

        // $client->send("query NickServ HELP REGISTER");
        // $r[] = $client->read(2048);

        // $client->send("MSG NickServ register test1234 test@test.com");
        // $r[] = $client->read(2048);

        sleep(1);
        $client->send("PRIVMSG {$this->channel} !request {$request->title}");
        $r[] = $client->read();

        // sleep(1);
        // while($i <= $limit && $do == 1):            
        //     $r[] = $client->read(1024);
        //     $i++;
        //     // sleep(1);
        // endwhile;

        $error = $client->getLastError();
        $client->close();

        $m = $client->getLogs();

        REQ::create([
            'fulltitle' => $request->title,
            'error'     => $error,
        ]);

        return response()->json([
            'error'    => $error,
            'message'  => "release.request_has_been_made",
            'log'      => app()->isLocal() ? $m : [],     
            'read'     => app()->isLocal() ? $r : [],  
            'client'   => app()->isLocal() ? $client : [],     
        ]);
    }

    public function isPing($r){
        if( strstr($r, 'PING')):
            $params = explode(':', $r);            

            if(count($params) == 2):
                $ping =  $params[1];
                $ping = str_ireplace(["\r", "\n"], '', $ping);

                $this->client->send("PONG {$ping}");
                return "Found PING sending PONG $ping";
            endif;
        endif;

        return "Is not a PING";
    }
  
}
