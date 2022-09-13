<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PragmaRX\Google2FAQRCode\Google2FA;

use App\Models\User;

use Str;
use Auth;

class G2FAController extends Controller
{

    public function activate(){
        $user = Auth::user();

        $qr = $this->generateNewSecret($user);

        return response()->json([
            'qr'  => $qr,
        ]);
    }

    public function disable(){
        $user = Auth::user();
        $user->g2fa = 0;
        $user->secret = null;
        $user->save();

        return response()->json([
            'message' => 'Disabled G2FA',
        ]);
    }


    public function get(){
        $user = Auth::user();

        if($user->g2fa == 0)
          return response()->json([ 'message' => "2FA not activated"], 400);

        $google2fa = new Google2FA();
        $inlineUrl = null;

        if(!$user->secret):
            $inlineUrl = $this->generateNewSecret($user);
        else:
            $inlineUrl = $this->getQr($user);
        endif;

        return response()->json([
            'qr'  => $inlineUrl,
        ]);
    }


    public function verify(Request $r){
        $user = Auth::user();

        $valid = (new Google2FA())->verifyKey($user->secret, $r->key, 4);

        return response()->json([
            'valid' => $valid,
        ], $valid ? 200 : 400);
    }


    public function generateNewSecret(User $user){
        $google2fa = new Google2FA();

        $user->g2fa   = 1;
        $user->secret = $google2fa->generateSecretKey();
        $user->save();

        return $this->getQr($user);
    }


    public function getQr(User $user){
        return (new Google2FA())->getQRCodeInline(
            $user->username . ' ' . env('APP_NAME'),
            $user->email,
            $user->secret,
        );
    }


}
