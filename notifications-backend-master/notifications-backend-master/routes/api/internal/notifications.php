<?php

Route::prefix('notifications')->namespace('API\Internal\Notifications')->group(static function () {
    Route::resource('list', 'ApiControllerInternalNotificationsList');

    Route::resource('events', 'ApiControllerInternalNotificationsEvents');

    Route::resource('sends', 'ApiControllerInternalNotificationsSend');
});
