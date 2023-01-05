<?php

/** @var Factory $factory */

use App\Models\Notifications\ModelNotificationsList;
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

$factory->define(ModelNotificationsList::class, function (Faker $faker) {
    return [
        'send_type' => $faker->word(),
        'data' => [
            'number' => $faker->randomNumber(),
            'num' => $faker->randomNumber(),
            'integer' => $faker->randomNumber(),
            'name' => $faker->title('male')
        ]
    ];
});
