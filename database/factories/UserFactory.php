<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => 'Tiago Oliveira',
        'email' => 'contatotiagobezerra@icloud.com',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'phone' => '11958727116‬',
        'api_token' => md5(str_random(60)),
        'picture' => "/img/profile.png"
    ];
});

$factory->state(App\User::class, 'gustavo', function ($faker) {
    return [
        'name' => 'Gustavo Troiano',
        'email' => 'gustavo@protectsite.com.br',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'phone' => '11984875460',
        'api_token' => '12e0b898fc794243ea01835819dad27b',
        'picture' => "/img/gustavo.JPG"
    ];
});

    $factory->state(App\User::class, 'jhony', function ($faker) {
        return [
            'name' => 'Jhony Duarte',
            'email' => 'jhony@cash4you.app',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'phone' => '11966341852',
            'api_token' => 'c864aa615fc55360f77bc278cadc76d7',
            'picture' => "/img/jhony.png"
        ];
});