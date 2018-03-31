<?php

use Faker\Generator as Faker;

$factory->define(App\GroupedBulb::class, function (Faker $faker) {
    return [
        'group_id' => function() {
            return factory(\App\Group::class)->create()->id;
        },
        'bulb_id' => function() {
            return factory(\App\Bulb::class)->create()->id;
        }
    ];
});
