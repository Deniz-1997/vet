<?php

/** @var Factory $factory */

use App\Models\Notifications\ModelNotificationsSend;
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

$factory->define(ModelNotificationsSend::class, function (Faker $faker) {
    return [
        'send' => $faker->boolean(),
        'sended_date' => $faker->dateTime(),
    ];
});
