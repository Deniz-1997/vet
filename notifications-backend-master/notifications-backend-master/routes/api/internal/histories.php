<?php

Route::prefix('histories')->namespace('API\Internal\Histories')->group(static function () {

    Route::resource('list', 'ApiControllerInternalHistories');

});
