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

Route::group(['prefix' => 'accounts'], function () {
    Route::post('/register', 'AccountController@register')->name('account.register');
    Route::post('/login', 'AccountController@login')->name('account.login');
    Route::post('/password/forgot', 'AccountController@forgotPassword')->name('account.password.forgot');
    Route::post('/password/reset', 'AccountController@resetPassword')->name('account.password.reset');
    Route::post('/invitation/accept', 'AccountController@acceptInvitation')->name('account.invitation.accept');
});

Route::group([
    'prefix'     => 'users',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('/profile', 'UserController@profile')->name('user.profile');
    Route::put('/profile/update', 'UserController@updateProfile')->name('user.update.profile');
    Route::put('/password/update', 'UserController@updatePassword')->name('user.update.password');
    Route::post('/picture/update', 'UserController@updateProfilePicture')->name('user.update.picture');
    Route::post('/invite', 'UserController@invite')->name('user.invite');
    Route::get('/logout', 'UserController@logout')->name('user.logout');
});

Route::get('/users', 'UserController@index')->name('users.index');

Route::group([
    'prefix'     => 'chats',
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('/send', 'ChatController@send')->name('chats.send');
});
