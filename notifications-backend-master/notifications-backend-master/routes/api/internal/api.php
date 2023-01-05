<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$path = realpath(dirname(__FILE__));
$cdir = scandir($path);
foreach ($cdir as $value) {
    $file = $path . DIRECTORY_SEPARATOR . $value;
    // если это "не точки" и не директория
    if (!in_array($value, array(".", ".."))
        && __FILE__ !== $file
        && is_file($file)
        && !is_dir($file)) {
        include $file;
    }
}
