<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;

use App\Exceptions\UserError;
use App\Models\User;
use Hash;

class AuthenticatedSessionController extends Controller
{

    // authenticate
    public function authenticate(Request $request, $next=null){
        $user = null;
        $match = 0;

        if($request->email):
            $user = User::where('email', $request->email);
        elseif($request->username):
            $user = User::where('email', $request->username)->orWhere('username', $request->username);
        else:
            throw new UserError("Login credentials missing. #422");
        endif;

        // Load User
        $user = $user->first();

        // check if user exists
        if(!$user)
            throw new UserError("Credentials doesn't match. #4");

        // check password
        if ($user && !Hash::check($request->password, $user->password)) {
            throw new UserError("Username or password doesn't match. #403");
        }

        // check if user is active
        if($user && $user->active == 0)
          throw new UserError("Account is inactive. Please contact Support.");


        if($user && $user->email_verified_at == null)
          throw new UserError("Account not verfied. Please verify your email first.");

        if($user->g2fa):
          if(strlen($request->totp) == 0)
            throw new UserError("Two Factor is enabled. You need to pass your valid time based TOTP Token", 428);

          if(!(new Google2FA())->verifyKey($user->secret, $request->totp, 4))
            throw new UserError("Your TOTP token doesn't match.");
        endif;

        return $user;
    }

    // Sanctum Login
    public function login(Request $request){
        $user = $this->authenticate($request);

        $token = (new Helper)->createUserToken($user);

        return response()->json([
            'message' => "user.login",
            'user'    => $user,
            'token'   => $token,
            'device'  => $request->header('User-Agent'),
        ], 200);
    }


    // Sanctum Logout
    public function logout(Request $request){
        $t = auth('sanctum')->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => "user.logout",
            'check'   => $t,
        ]);
    }



    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }


    /// crate a auth init
    public function init(Request $r){
        $key = config('app.GOOGLE_RECAPTCHA_SITE_KEY');

        return response()->json([
            'key'   => $key,
        ]);
    }

}
