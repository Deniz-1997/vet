<?php

Route::prefix('dictionary')->namespace('API\Internal\Dictionary')->group(static function () {

    Route::resource('group-users', 'ApiControllerInternalDictionaryGroupUsers');

    Route::resource('histories-type', 'ApiControllerInternalDictionaryHistoriesType');

    Route::resource('organizations', 'ApiControllerInternalDictionaryOrganizations');

});
