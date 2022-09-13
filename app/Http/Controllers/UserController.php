<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Auth\Helper as AuthHelper;

use Str;
use Auth;
use Storage;
use Hash;
use App\Models\User;

class UserController extends Controller
{

    public function getUser(){
        $user = User::where('id', Auth::id())
                ->withCount('unreadNotifications')
                ->firstOrFail();

        return response()->json([
          'user' => $user,
        ]);
    }

    public function getTokens(){
        $user = Auth::user();

        $tokens = $user->tokens()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'items' => $tokens,
        ]);
    }

    public function createToken(){
        $user = Auth::user();

        $token = (new AuthHelper())->createUserToken($user, 'upper');

        $tokens = $user->tokens()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'item'  => $token,
            'items' => $tokens,
        ]);
    }

    public function clearToken(Request $r){
        $user = Auth::user();

        if(!$user)
            return response()->json([
                'message'   => "No valid user auth.",
            ], 401);

        $found = $user->tokens()->where('id', $r->id)->first();
        if($found)
            $found->delete();

        $tokens = $user->tokens()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'found' => $found,
            'items' => $tokens,
        ]);        
    }


    public function update(Request $r){
        // Validate Location
        $s = Validator::make($r->all(), [ 'settings'  => 'check_array:1' ]);
        $p = Validator::make($r->all(), [ 'profile'  => 'check_array:1' ]);

        $user = User::where('id', Auth::id())
                      ->with('profileSettings', 'settingSettings')
                      ->firstOrFail(); // #todo - load user directly with specific guard

        // settings validation and save
        if($r->has('settings')):
            // $user->settingSettings()->updateOrCreate(['key' => 'profile.settings'], $r->input('settings'));
            $settings = array_merge($user->settings,$r->input('settings'));

            // Nessesary keys
            $keys = ['fc_api_key', 'imdb_api_key', 'show_notification_on_new_entry'];

            // validate settings here
            foreach($keys as $key):
                if( !$r->exists('settings.' . $key))
                  $settings[$key] = true;
            endforeach;

            $user->saveSettings('settingSettings', 'profile.settings', $settings);
        endif;

        // profile validation and update
        if(!$p->fails() && $r->has('profile')):
            $user->saveSettings('profileSettings', 'profile.profile', $r->input('profile'));
        endif;

        $user->refresh();

        return response()->json([
            'user'      => $user,
        ]);
    }


    public function setupDone(Request $r){
        $user = Auth::user();
        $user->update(['gain_access' => 1]);

        return response()->json([
            'message' => "user.setup_done",
            'user'    => $user,
        ]);
    }


    public function avatar(Request $r){
        $r->validate([
            'image' => 'required',
        ]);

        $user = Auth::user();
        $hash = env('APP_ENV', 'local')[0] . '_' . $user->id; // $user->getHash();

        $file = $r->file('image');
        $name = $file->getClientOriginalName();
        $path = $file->getRealPath();
        $size = $file->getSize();
        $mime = $file->getMimeType();
        $ext  = $file->getClientOriginalExtension();

        $base = 'users/' . $hash . '/avatars'; // . $user->created_at->format('Y/m');
        $filename = Str::random(10) . '_avatar.' . $ext;
        $path = $base . '/' . $filename;

        $current_avatar = $user->getRawOriginal('avatar');

        // // save new image first
        // // Storage::disk('public')->putFileAs($base, $file, $filename);
        // $storage_path = Storage::disk('s3')->put($base, $file, 'public');
        //
        // // delete the old image first
        // if($current_avatar && Storage::disk('s3')->exists($current_avatar)):
        //   Storage::disk('s3')->delete($current_avatar);
        // endif;

        $storage_path = Storage::disk('public')->putFileAs($base, $file, $filename);
        // $storage_path = Storage::disk('public')->put($base, $file, 'public');

       // delete the old image first
       if($current_avatar && Storage::disk('public')->exists($current_avatar)):
         Storage::disk('public')->delete($current_avatar);
       endif;


        // update the user
        $user->update(['avatar' => $path ]);
        $user->refresh();

        return response()->json([
            'message' => 'user.avatar_updated',
            'user'    => $user,
        ]);
    }


}
