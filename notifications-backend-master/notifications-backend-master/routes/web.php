<?php

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


Route::get('/', function () {
    return view('layouts/app');
});

Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::get('files/md5/{md5}', 'FilesController@getByMD5')->middleware([
    'auth:userapi',
    'throttle:rate_limit,1'
]);

Route::prefix('api/notifications')
    ->middleware('auth:api_channel')
    ->namespace('API\External\Notifications')
    ->group(static function () {
        Route::resource('list', 'ApiControllerExternalNotificationsList');

        Route::get('receiver/{id}', 'ApiControllerExternalNotificationsReceiver@get');
        Route::post('receiver', 'ApiControllerExternalNotificationsReceiver@index');
        Route::get('events', 'ApiControllerExternalNotificationsReceiver@events');
    });

Route::prefix('api/user')->namespace('API\External\User')->group(static function () {
    Route::post('auth', 'ApiControllerExternalUser@auth');
});

Route::prefix('api/sms')->namespace('API\External\User')->group(static function () {
    Route::post('check/{hash}', 'ApiControllerExternalUser@smsCheck');
});
