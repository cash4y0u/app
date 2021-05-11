<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->firstNameMale ." ". $faker->lastName,
        'birth' => $faker->date('Y-m-d', '1992-01-01'),
        'document' => $faker->cpf(true),
        'email' => $faker->email,
        'phone' => $faker->phone,
        'adresses' => [

            [
                'type' => (string) Arr::first(Arr::random(['Casa', 'Trabalho', 'Empresa'], 1) ),
                'zipcode' => $faker->postcode,
                'street' => $faker->streetName,
                'number' => $faker->buildingNumber,
                'complement' => '',
                'district' => $faker->citySuffix,
                'city' => $faker->city,
                'state' => $faker->state,
                'favorite' => true,
            ]
        
        ],
        'photo' => "https://randomuser.me/api/portraits/men/".rand(1, 95).".jpg"
    ];
});
$factory->state(App\Customer::class, 'real', function ($faker) {
    return [
        'name' => 'Macleide Araujo da Silva',
        'birth' => '1977-11-11',
        'document' => '324.670.838-02',
        'email' => 'macleide@hotmail.com',
        'phone' => '11952226183',
        'adresses' => [
            [
                'type' => (string) Arr::first(Arr::random(['Casa', 'Trabalho', 'Empresa'], 1)),
            'zipcode' => '08040-275',
            'street' => 'R. Sararaca',
            'number' => '118',
            'complement' => (string) Arr::first(Arr::random(['Terreno', 'Fundos', 'Apartamento'], 1)),
            'district' => 'São Miguel',
            'city' => 'São Paulo',
            'state' => 'SP',
            'favorite' => true,
            ]
        ],
        'photo' => "/img/macleide.jpeg"
    ];
});