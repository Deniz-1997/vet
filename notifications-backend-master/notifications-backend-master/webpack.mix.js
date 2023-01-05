const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.combine([
    'public/js/prototypes.js',
    'public/js/luxon.min.js',
    'public/js/rest-client.js',
    'public/js/app.js',
    'public/js/libs.min.js',
    'public/js/main.js',
], 'public/js/app_.js');
mix.version();
