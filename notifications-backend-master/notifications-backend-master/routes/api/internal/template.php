<?php

Route::prefix('template')->namespace('API\Internal\Templates')->group(static function () {
    Route::resource('list', 'ApiControllerInternalTemplateList');
    Route::resource('group-user', 'ApiControllerInternalTemplateGroupUser');
});
