<?php

use Carbon\Carbon;

use Illuminate\Support\Arr;
use Faker\Generator as Faker;

$factory->define(App\Contract::class, function (Faker $faker) {

    $amount = 40000;
    $split = 20;
    $cycle = Arr::random(['monthly'], 1);
    $rate = 1.9;
    $rate_daily = 3.0;

    $e = 1.0;
            $cont = 1.0;
            for($k = 1; $k <= $split; $k++){
               $cont = bcmul($cont, bcadd(bcdiv($rate, 100, 15), 1, 15), 15);
               $e = bcadd($e, $cont, 15);
            }
            $e = bcsub($e, $cont, 15);
        
            $amount_provision = bcdiv(bcmul($amount, $cont, 15), $e, 15);
        
            $amount_rate = $amount_provision - ($amount / $split);
        
            $amount_profit =  ($amount_provision * $split) - $amount;
        
            $amount_total =  $amount_provision * $split;

    return [
        'customer_id' => App\Customer::inRandomOrder()->first()->id,
        'amount' => $amount,
        'amount_provision' => $amount_provision,
        'amount_rate' => $amount_rate,
        'amount_total' => $amount_total,
        'amount_profit' => $amount_profit,
        'split' => $split,
        'cycle' => Arr::first($cycle),
        'rate' => $rate,
        'rate_daily' => $rate_daily,
        'maturity' => Carbon::parse('2020-01-01 18:34:51'),
        'status' => 'open',
    ];
});