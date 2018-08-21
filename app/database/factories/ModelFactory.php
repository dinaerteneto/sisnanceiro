<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Person::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => $faker->randomElement(['M', 'F'])
    ];
});

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'email' => $faker->unique()->safeEmail,
        'password' => $password ? : $password = bcrypt('secret'),
    ];
});
