<?php

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

Route::group(
    ['prefix' => 'accounts'],
    function () {
        Route::post('/register', 'UserController@register')->name('account.register');
        Route::post('/login', 'UserController@login')->name('account.login');
        Route::post('/password/reset', 'UserController@resetPassword')->name('account.password.reset');
        Route::post('/invite', 'UserController@inviteUser')->name('account.invite');
    }
);

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', 'UserController@profile')->name('user.profile');
});
