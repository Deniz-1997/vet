<?php

Route::prefix('channels')->namespace('API\Internal\Channels')->group(static function () {

    Route::resource('administrators', 'ApiControllerInternalChannelsAdministrators');

    Route::resource('apis', 'ApiControllerInternalChannelsApi');

    Route::resource('event', 'ApiControllerInternalChannelsEvent');

    Route::resource('list', 'ApiControllerInternalChannelsList');

    Route::resource('users', 'ApiControllerInternalChannelsUsers');

});
