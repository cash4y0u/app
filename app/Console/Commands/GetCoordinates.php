<?php

namespace App\Console\Commands;

use App\Provision;
use App\Library\Geocode;
use Illuminate\Console\Command;
use App\Http\Resources\ProvisionResource;

class GetCoordinates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drive:coordinates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtem coordenadas';

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
        $this->info("Start...");

        $maps = new Geocode();

        $provisions = Provision::with('contract')
        ->whereDate('maturity', now())
        ->where('status', 'pending')
        ->whereNull('coordinates')
        ->get();

    foreach($provisions as $provision){
        foreach($provision->contract->customer->adresses as $address){

            if($address['favorite']){

                $street = $address['street'];
                $number = $address['number'];
                $district = $address['district'];

                $maps->to("{$street} {$number} {$district}");

                $provision->update([
                    'coordinates' => $maps->location()
                ]);

                $this->info("Search: {$street} {$number} {$district}");

            }
        }
    }
    $this->info("Finish!");
    }
}
