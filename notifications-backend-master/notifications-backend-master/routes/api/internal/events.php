<?php

Route::prefix('events')->namespace('API\Internal\Events')->group(static function () {
    Route::resource('list', 'ApiControllerInternalEventsList');
    Route::resource('template', 'ApiControllerInternalEventsTemplates');
});
