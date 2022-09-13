<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IRCController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MemberController;
use Illuminate\Session\Middleware\StartSession;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/hb', function(){
    return response()->json([
        'message' => 'Heartbeat is working',
    ]);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

], function(){
  Route::get('app/config/get', [AdminController::class, 'getApplicationConfig'])->name('app.getApplicationConfig');

  Route::get('test/live', function(){
      broadcast( new \App\Events\LiveEvent() );
  })->middleware('throttle:apiRate10')->name('ws.test.live.generate');
});


// Authenticated
Route::group([
  'middleware' => ['auth:api', 'isAdminModDev'],
], function(){  
  Route::get('countries/all', [CountryController::class, 'all'])->name('countries.all');
  
});


// Authenticated for Admins and Mods
Route::group([
  'middleware' => ['auth:api', 'isAdminModDev'],
], function(){

  Route::get('entry/allApps', [EntryController::class, 'allApps'])->name('entry.allApps');
  Route::post('entry/verify', [EntryController::class, 'verify'])->name('entry.verify');
  Route::post('entry/delete', [EntryController::class, 'delete'])->name('entry.delete');  

  Route::post('release/reassignToEntry', [ReleaseController::class, 'reassignToEntry'])->name('release.reassignToEntry');
  
  Route::get('members/getUsersToAssign', [MemberController::class, 'getUsersToAssign'])->name('member.users.getUsersToAssign');
});

// Authenticated for Admins
Route::group([
  'middleware' => ['auth:api', 'isAdmin'],
], function(){
  Route::get('members', [MemberController::class, 'all'])->name('member.user.all');

  Route::patch('member/update', [MemberController::class, 'update'])->name('member.user.update');
  Route::patch('member/{user}/grant', [MemberController::class, 'grant'])->name('member.user.grant');
  Route::patch('member/{user}/block', [MemberController::class, 'block'])->name('member.user.block');

  Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');

  Route::get('app/footer/get', [AdminController::class, 'getFooter'])->name('app.footer.get');
  Route::post('app/footer/save', [AdminController::class, 'saveFooter'])->name('app.footer.save');
});
