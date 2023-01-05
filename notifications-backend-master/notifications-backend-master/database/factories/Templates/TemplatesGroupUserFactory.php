<?php

/** @var Factory $factory */

use App\Models\Templates\ModelTemplatesGroupUser;
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

$factory->define(ModelTemplatesGroupUser::class, function (Faker $faker) {
    return [
        'priority' => $faker->numberBetween(1, 100)
    ];
});
