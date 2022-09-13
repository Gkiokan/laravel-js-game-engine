<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Auth\AuthController;

use App\Models\User;

use URL;
use Auth;
use Str;
use Arr;
use App;
use Log;
use Carbon\Carbon;


class VerifyController extends Controller
{

    var $keyResolver;

    public function __construct()
    {
        $this->keyResolver = function () {
            return App::make('config')->get('app.key');
        };
    }

    public function hasValidSignature(Request $request, $absolute = true)
    {
          $url = $absolute ? $request->url() : '/'.$request->path();

          $url = str_replace('http://', 'https://', $url);

          $original = rtrim($url.'?'.Arr::query(
              Arr::except($request->query(), 'signature')
          ), '?');

          $expires = Arr::get($request->query(), 'expires');

          $signature = hash_hmac('sha256', $original, call_user_func($this->keyResolver));

          // Log::info('url: '.$original);
          // Log::info('expire: '.$expires);
          // Log::info('new signature: '.$signature);
          // Log::info('link signature: '.$request->query('signature', ''));
          // Log::info('hash equals: '.hash_equals($signature, $request->query('signature', '')));
          // Log::info('expired: '.!($expires && Carbon::now()->getTimestamp() > $expires));

          return  hash_equals($signature, (string) $request->query('signature', '')) &&
                 ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }


    public function verifyEmail(Request $request, User $id){
        // Handle all the validation and mark as verified when all correct
        // $request->fulfill();

        if (! $this->hasValidSignature($request)) {
            $url = $request->url();

            $original = rtrim($url.'?'.Arr::query(
                Arr::except($request->query(), 'signature')
            ), '?');

            $expires = Arr::get($request->query(), 'expires');

            $signature = hash_hmac('sha256', $original, call_user_func($this->keyResolver));

            return response()->json([
                'status' => 'verification.invalid',
                'code'   => 1,
                'original' => $original,
                'link signature' => $request->query('signature', ''),
                'new signature'  => $signature,
                'hash equals'    => hash_equals($signature, $request->query('signature', '')),
                'expired'        => !($expires && Carbon::now()->getTimestamp() > $expires),
                'request'  => $request->all(),
                // 'user'   => $user,
            ], 400);
        }

        $user = $id;

        if ($user->hasVerifiedEmail()) {
            // $token = $this->createToken($user);

            return response()->json([
                'status' => 'verification.already_verified',
                'user'   => $user,
                'token'  => $this->createToken($user),
                'code'   => 2,
            ], 400);
        }

        $user->markEmailAsVerified();
        $user->active      = 1;
        $user->role        = 'user';
        $user->activate_at = Carbon::now();
        $user->activate_token = 'AUT::' . Str::random();
        $user->save();

        // event(new Verified($user));

        $token = $this->createToken($user);

        return response()->json([
            'status' => 'verification.verified',
            'user'   => $user,
            'token'  => $token,
            'code'   => 3,
        ]);
    }



    public function resetEmail(Request $request, $email=null){
        $resetPassword = "fu";

        if($request->input('pass') !== $resetPassword){
            return response()->json([ 'message' => 'No access'], 401);
        }

        $user = User::where('email', $email)->firstOrFail();

        $user->active      = 1;
        $user->activate_at = null;
        $user->activate_token = null;
        $user->email_verified_at = null;

        $user->save();

        return response()->json([
            'message' => "User $email has been reset",
        ]);
    }


    public function createToken(User $user){
        return (new Helper)->createUserToken($user);
    }


    public function verifyEmailResend(Request $request){
        $user = User::where('email', $request->input('email'))->firstOrFail();
        $send = false;

        if($user->email_verified_at):
            return response()->json([
                'message' => 'verification.already_verified',
            ]);
        endif;

        $user->sendEmailVerificationNotification();
        $send = true;

        return response()->json([
            'message' => 'verification.verification_mail_send',
            'send'  => $send,
        ]);
    }

}
