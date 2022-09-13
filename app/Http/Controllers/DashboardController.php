<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Release;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index(Request $r){

        $count_entries  = Entry::count();
        $count_releases = Release::count();
        $count_users    = User::count();
        $count_upper    = User::where('role', 'upper')->count();

        $count_views    = Entry::max('views');
        $count_dl       = Entry::max('downloads');

        return response()->json([
            'count_entries'     => $count_entries,
            'count_releases'    => $count_releases,
            'count_users'       => $count_users,
            'count_upper'       => $count_upper,
            'count_views'       => $count_views,
            'count_dl'          => $count_dl,
        ]);
    }
}
