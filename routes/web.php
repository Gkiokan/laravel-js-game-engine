<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WebController;
use App\Http\Controllers\AttachmentController;

use App\Models\Entry;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return ['Laravel' => app()->version()];
// });

/*
    Base
    Public store website
*/
Route::group([
  'domain' => config('app.APP_URL'),
], function(){

    // Route::get('patch', function(){
    //   $items = Entry::all();

    //   foreach($items as $item):
    //       $genres = $item->genre;
    //       $newArray = [];
          
    //       foreach($genres as $g)
    //           $newArray[] = isset($g['value']) ? $g['value'] : $g;

    //       $item->genre = $newArray;
    //       $item->save();

    //   endforeach;
    // });

    Route::get('/wp-admin/{any?}', [WebController::class, 'wplogin'])->where('any', '.*')->name('app.wp-login');
    // Route::get('/wp-includes/{any?}', [WebController::class, 'wpIncludes'])->where('any', '.*')->name('app.wp-includes');

    Route::get('/attachments/stream/{asset?}', [AttachmentController::class, 'stream'])->where('asset', '.*')->name('attachment.stream.get');
    Route::get('/attachments/{asset?}', [AttachmentController::class, 'get'])->where('asset', '.*')->name('attachment.get');
    Route::get('/{any?}', [WebController::class, 'app'])->where('any', '.*')->name('app.index');
});


// overall fallback
Route::get('/{any?}', [WebController::class, 'app'])->where('any', '.*')->name('index');
