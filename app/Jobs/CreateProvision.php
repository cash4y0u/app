<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Contract;
use Carbon\Carbon;

class CreateProvision implements ShouldQueue
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
        for ($i = 0; $i < $this->contract->split; $i++) {
            switch ($this->contract->cycle) {
                case 'daily':
                    $maturity = Carbon::parse($this->contract->maturity)->addDay($i);
                    break;
                case 'weekly':
                    $maturity = Carbon::parse($this->contract->maturity)->addWeek($i);
                    break;
                case 'fortnightly':
                    $maturity = Carbon::parse($this->contract->maturity)->addWeek(2 * $i);
                    break;
                case 'monthly':
                    $maturity = Carbon::parse($this->contract->maturity)->addMonth($i);
                    break;
            }
            $this->contract->provisions()->create([
                'number' => $i + 1,
                'amount' => $this->contract->amount_provision,
                'maturity' => $maturity
            ]);
        }
    }
}
