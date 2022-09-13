<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class Helper extends Controller
{
    public function createUserToken(User $user, $type=null){
        $type = $type ? $type : (request()->header('User-Agent') ?: 'app');
        return $token = $user->createToken($type)->plainTextToken;
    }
}
