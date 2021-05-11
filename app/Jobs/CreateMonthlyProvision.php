<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Contract;

class CreateMonthlyProvision implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contract;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dates = [];
        $number = 1;
        for ($i = 0; $i < $this->contract->split; $i++) {
            $maturity = $this->contract->maturity->addMonth($i);
            $this->contract->provisions()->create([
                'number' => $number++,
                'amount' => $this->contract->amount_provision,
                'maturity' => ($maturity->isSunday() ? $maturity->addDay(1) : $maturity)
            ]);
        }
  
    }
}
