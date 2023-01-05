<?php

/** @var Factory $factory */

use App\Models\Channels\ModelChannelsApi;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ModelChannelsApi::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'api_token' => \Illuminate\Support\Str::random(60),
    ];
});
