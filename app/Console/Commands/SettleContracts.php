<?php

namespace App\Console\Commands;

use App\Contract;
use Illuminate\Console\Command;

class SettleContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:settle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica se todas parcelas foram pagas e quita o contrato';

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
        foreach (Contract::with('provisions')->where('status', 'open')->get() as $contract) {

            if($contract->provisions->count() == $contract->provisions->where('status', 'paid')->count()){
                return $contract->update([
                     'status' => 'settled'
                 ]);
            }
    }
    }
}
