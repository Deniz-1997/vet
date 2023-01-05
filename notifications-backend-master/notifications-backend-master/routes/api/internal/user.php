<?php

Route::prefix('user')
    ->namespace('API\Internal\User')->group(static function () {

        Route::get('get', function (\Illuminate\Http\Request $request) {
            return (new App\Http\Controllers\API\ApiController())->sendResponse(Auth()->user()->toArray(), $request);
        })->middleware(['auth']);

        Route::resource('list', 'ApiControllerInternalUserList');

        Route::resource('devices', 'ApiControllerInternalUserDevices');

        Route::resource('roles', 'ApiControllerInternalRoles');
    });
