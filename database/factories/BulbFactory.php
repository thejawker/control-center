<?php

use Faker\Generator as Faker;

$factory->define(App\Bulb::class, function (Faker $faker) {
    return [
        'ip' => $faker->ipv4,
        'device_id' => $faker->uuid,
        'model' => 'model-a'
    ];
});
