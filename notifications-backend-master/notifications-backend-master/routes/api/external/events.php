<?php

Route::prefix('events')->namespace('API\External\Event')->group(static function () {
    Route::get('icons', 'ApiControllerExternalEventIcons@get');
});
