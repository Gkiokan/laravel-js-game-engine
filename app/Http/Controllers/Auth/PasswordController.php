<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Validation\Rules;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

use App\Models\User;

use Auth;
use Str;

class PasswordController extends Controller
{

    public function changePassword(Request $r){
        $user = Auth::user();
        $user = User::where('id', Auth::id())->first(); //->makeVisible('initial_password');

        $message = '';
        $r->validate([
            'password'  => ['required', 'string', 'confirmed'],
            'current_password' => ['required'],
        ]);
        $input = $r->only(['current_password', 'password', 'password_confirmation']);


        // If current password matches initial password, lets verify the user
        if($user->initial_password == $r->current_password && !$user->mobile_verified_at):
            $message = "auth.initial_password";
            $user->active = true;
            $user->password = Hash::make($r->input('password'));
            $user->mobile_verified_at = \Carbon\Carbon::now();
            $user->save();
        else:
            // Update user Password
            $message = "auth.password_changed";
            // (new UpdateUserPassword)->update($user, $input);

            Validator::make($input, [
                'current_password' => ['required', 'string'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ])->after(function ($validator) use ($user, $input) {
                if (! isset($input['current_password']) || ! Hash::check($input['current_password'], $user->password)) {
                    $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
                }
            })->validateWithBag('updatePassword');

            $user->forceFill([
                'password' => Hash::make($r->input('password')),
            ])->save();
        endif;

        return response()->json([
            'message' => $message,
            'user'  => $user->refresh(),
        ]);
    }


    public function forgotPassword(Request $r){
        $r->validate([
            'email' => 'required',
        ]);

        $user = User::where('email', $r->email)->firstOrFail();

        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);

        return response()->json([
            'message' => 'auth.forgot_mail_has_been_send',
        ]);
    }


    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required',
            'password'  => 'required',
            'password_confirmation' => 'required',
            'token' => 'required',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();


        $status = Password::reset(
              $request->only('email', 'password', 'password_confirmation', 'token'),
              function ($user, $password) {
                  $user->forceFill([
                      'password' => Hash::make($password)
                  ]);

                  $user->save();
                  event(new PasswordReset($user));
              }
          );

        return response()->json([
            'message' => 'auth.password_has_been_reset',
            'status'  => $status,
        ]);
    }

}
