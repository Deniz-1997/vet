<?php

Route::prefix('user')->namespace('API\External\User')->group(static function () {

    Route::post('logout', 'ApiControllerExternalUser@logout');

    Route::post('info', 'ApiControllerExternalUser@updateInfo');

    Route::get('info', 'ApiControllerExternalUser@info');

    Route::get('channels', 'ApiControllerExternalUserChannels@index');

    Route::get('events', 'ApiControllerExternalUserEvents@index');

    Route::get('template', 'ApiControllerExternalUserTemplate@index');

    Route::get('notifications', 'ApiControllerExternalUserNotifications@index');

    Route::post('notifications/{id}', 'ApiControllerExternalUserNotifications@update');
});
