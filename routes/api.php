<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'accounts'], function () {
        Route::controller(AccountController::class)->group(function () {
            Route::post('/register', 'register')->name('account.register');
            Route::post('/login', 'login')->name('account.login');
            Route::post('/password/forgot', 'forgotPassword')->name('account.password.forgot');
            Route::post('/password/reset', 'resetPassword')->name('account.password.reset');
            Route::post('/invitation/accept', 'acceptInvitation')->name('account.invitation.accept');
        });
    });

    Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/profile', 'profile')->name('user.profile');
            Route::put('/profile/update', 'updateProfile')->name('user.update.profile');
            Route::put('/password/update', 'updatePassword')->name('user.update.password');
            Route::post('/picture/update', 'updateProfilePicture')->name('user.update.picture');
            Route::post('/invite', 'invite')->name('user.invite');
            Route::get('/logout', 'logout')->name('user.logout');
        });
    });

    Route::group(['prefix' => 'messages', 'middleware' => ['auth:sanctum']], function () {
        Route::controller(MessageController::class)->group(function () {
            Route::post('/send', 'send')->name('messages.send');
            Route::post('/delete/{id}', 'delete')->name('conversation.messages.delete');

            Route::group(['prefix' => 'conversations'], function () {
                Route::get('/', 'conversations')->name('user.conversations');
                Route::get('/{id}', 'messages')->name('conversation.messages');
            });
        });
    });
});
