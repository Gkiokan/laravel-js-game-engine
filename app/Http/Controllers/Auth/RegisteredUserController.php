<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use Http;

class RegisteredUserController extends Controller
{

    public $c = "https://www.google.com/recaptcha/api/siteverify";

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        // $secret = config('app.GOOGLE_RECAPTCHA_SECRET_KEY');
        //
        // $res = Http::get($this->c . '?' . http_build_query([
        //     'secret'    => $secret,
        //     'response'  => $request->token,
        // ]));
        //
        // $json = $res->json();
        //
        // if(!$json['success'])
        //     return response()->json(['message' => 'reCaptcha failed'], 406);


        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'avatar'   => null,
            'active'   => 1,
            'role'     => 'user',
            'password' => Hash::make($request->password),
        ]);

        $user->saveSettings('settingSettings', 'profile.settings', []);

        event(new Registered($user));

        // Auth::login($user); // depcreated because we use sanctum and not session based laravel auth

        $token = (new Helper)->createUserToken($user);

        // return response()->noContent(); // deprecated
        return response()->json([
            'message' => "user.created",
            'user'    => $user,
            'token'   => $token,
            'device'  => $request->header('User-Agent'),
        ], 201);
    }
}
