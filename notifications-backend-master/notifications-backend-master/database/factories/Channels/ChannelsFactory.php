<?php

/** @var Factory $factory */

use App\Models\Channels\ModelChannels;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ModelChannels::class, function (Faker $faker) {
    return [
        'sms_limit' => $faker->numberBetween(5, 10),
        'email_limit' => $faker->numberBetween(5, 10),
        'send_sms' => $faker->boolean(),
        'send_email' => $faker->boolean(),
        'name' => $faker->name(),
    ];
});
