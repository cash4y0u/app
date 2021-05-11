<?php

namespace App\Console\Commands;

use App\Provision;
use App\Library\Maps;
use Illuminate\Console\Command;
use App\Http\Resources\ProvisionResource;

class CalculateTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drive:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcular tempo de chegada no endereço do cliente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $provisions = Provision::with('contract')
        ->whereDate('maturity', now())
        ->where('status', 'pending')
        ->whereNull('time')
        ->get();

    foreach($provisions as $provision){
        foreach($provision->contract->customer->adresses as $address){

            if($address['favorite']){

                $street = $address['street'];
                $number = $address['number'];
                $district = $address['district'];

                $maps = new Maps();
                $maps->to("{$street} {$number} {$district}");

                $provision->update([
                    'time' => $maps->time()
                ]);

                $this->info("Search: {$street} {$number} {$district}");

            }
        }
    }
    }
}
