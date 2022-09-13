<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SimpleUser;

use Carbon\Carbon;
use Str;

class MemberController extends Controller
{

    public function all(Request $r){
        // $users = User::all();

        if($r->has('search') && $r->search)
          $users = User::where('username', 'LIKE', "%{$r->search}%")
                    ->orWhere('email', 'LIKE', "%{$r->search}%")
                    ->paginate();
        else
          $users = User::paginate();

        return response()->json([
            'users' => $users,
        ]);
    }


    public function getUsersToAssign(){
        $users = SimpleUser::orderBy('username', 'asc')->where('role', '!=', 'user')->get(['id', 'username', 'avatar', 'role']);

        return response()->json([
            'items' => $users,
        ]);
    }


    public function update(Request $r){
        $user = User::where('id', $r->id)->firstOrFail();

        if($r->email_verified_at && !$user->email_verified_at):
            $user->activate_at = Carbon::now();
            $user->activate_token = 'MAN::' . Str::random();
            $user->gain_access = true;
            $user->save();
        endif;

        if($r->email != $user->email)
            if(User::where('email', $r->email)->exists())
                return response()->json([ 'message' => 'email.already_taken'], 422);
            else 
                $user->update(['email' => $r->email]);

        if($r->has('password'))
            if( strlen($r->input('password') ) )
                $user->update(['password' => bcrypt($r->input('password')) ]);

        $user->update( $r->only(['role', 'active', 'email_verified_at']) );

        return response()->json([
            'user'  => $user,
            'message' => 'user.updated',
        ]);
    }


    public function grant(Request $r, User $user){
        $user->update(['active' => 1]);

        return response()->json([
            'message' => "user.activated",
        ]);
    }

    public function block(Request $r, User $user){
        $user->update(['active' => 0]);

        return response()->json([
            'message' => "user.blocked",
        ]);
    }

}
