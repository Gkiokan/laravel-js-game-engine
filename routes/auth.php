<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\G2FAController;

use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('throttle:apiRate10')
                ->middleware('guest');

Route::post('/login', [AuthenticatedSessionController::class, 'login'])
                ->middleware('throttle:apiRate10')
                ->middleware('guest');

Route::post('/init', [AuthenticatedSessionController::class, 'init'])
                ->middleware('throttle:apiRate10')
                ->middleware('guest');

// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//                 ->middleware('guest')
//                 ->name('password.email');

// Route::post('/reset-password', [NewPasswordController::class, 'store'])
//                 ->middleware('guest')
//                 ->name('password.update');

// Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//                 ->middleware(['auth', 'signed', 'throttle:6,1'])
//                 ->name('verification.verify');

// Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//                 ->middleware(['auth', 'throttle:6,1'])
//                 ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])
                ->middleware('throttle:apiRate10')
                ->middleware('auth')
                ->name('logout');


// Auth stuff
Route::post('verify/email/resend', [VerifyController::class, 'verifyEmailResend'])->middleware('throttle:apiRate10')->name('verification.verify.email.resend');
Route::post('verify/email/{id}/{hash}', [VerifyController::class, 'verifyEmail'])->middleware('throttle:api')->name('verification.verify.email');
Route::get('verify/email/reset/{email}', [VerifyController::class, 'resetEmail'])->middleware(['throttle:apiRate10'])->name('verification.verify.reset.email');

// Password stuff
Route::post('password/forgot', [PasswordController::class, 'forgotPassword'])->name('password.forgot');
Route::post('password/reset', [PasswordController::class, 'resetPassword'])->name('password.reset');

// User based operations
Route::group([
    'prefix' => 'user',
    'middleware' => ['auth'],
], function(){
    Route::get('/', [UserController::class, 'getUser'])->name('get.user');
    Route::post('/update', [UserController::class, 'update'])->name('user.update');
    Route::post('/avatar', [UserController::class, 'avatar'])->name('user.avatar');
    Route::post('/tokens', [UserController::class, 'getTokens'])->name('user.tokens');
    Route::post('/token/create', [UserController::class, 'createToken'])->name('user.createToken');
    Route::post('/token/clear', [UserController::class, 'clearToken'])->name('user.clearToken');
    Route::post('/password/change', [PasswordController::class, 'changePassword'])->name('user.password.change');
    Route::post('/setup/done', [UserController::class, 'setupDone'])->name('user.setupDone');

    Route::get('/2fa/get', [G2FAController::class, 'get'])->name('user.2fa.get');
    Route::post('/2fa/verify', [G2FAController::class, 'verify'])->name('user.2fa.verify');
    Route::post('/2fa/activate', [G2FAController::class, 'activate'])->name('user.2fa.activate');
    Route::post('/2fa/disable', [G2FAController::class, 'disable'])->name('user.2fa.disable');
});
