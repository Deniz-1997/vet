<?php

/** @var Factory $factory */

use App\Models\Templates\ModelTemplatesList;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(ModelTemplatesList::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'text' => $faker->text(),
        'color' => $faker->hexColor,
        'format_date' => 'YYYY-MM-DD H:i:s',
        'show_status_notify' => $faker->boolean(),
        'show_date' => $faker->boolean(),
        'show_time' => $faker->boolean(),
    ];
});
