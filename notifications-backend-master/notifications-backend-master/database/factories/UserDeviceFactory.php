<?php

/** @var Factory $factory */

use App\Models\User\ModelUserDevices;
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

$factory->define(ModelUserDevices::class, function (Faker $faker) {
    return [
        'token' => \Illuminate\Support\Str::random(60),
        'reg_id' => $faker->uuid,
        'access_key' => $faker->uuid
    ];
});
