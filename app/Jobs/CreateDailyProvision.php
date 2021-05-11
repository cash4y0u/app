<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Contract;
use Carbon\Carbon;

class CreateDailyProvision implements ShouldQueue
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
        for ($i = 0; $i < 500; $i++) {
            if(!$this->contract->maturity->addDay($i)->isSunday()){
                $dates[] = $this->contract->maturity->addDay($i)->format('Y-m-d');
            }
        }
        $number = 1;
            foreach (collect($dates)->take($this->contract->split) as $date) {
            $this->contract->provisions()->create([
                'number' => $number++,
                'amount' => $this->contract->amount_provision,
                'maturity' => $date
            ]);
            }
    }
}
