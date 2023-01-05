<?php

Route::prefix('file')->namespace('API\Internal\File')->group(static function () {
    Route::resource('list', 'ApiControllerInternalFileList');
});
