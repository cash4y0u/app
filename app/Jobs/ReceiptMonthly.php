<?php

namespace App\Jobs;

use App\Contract;
use App\Provision;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReceiptMonthly implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $provision;
    protected $amount_paid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Provision $provision, $amount_paid)
    {
        $this->provision = $provision;
        $this->amount_paid = $amount_paid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Se o valor for igual o da parcela
        if($this->amount_paid == $this->provision->amount){
            $this->provision->update([
                'amount_paid' => $this->provision->amount,
                'received_at' => now()
            ]);
        }

       
        // se o valor pago for menor que o valor da parcela
        if($this->amount_paid < $this->provision->amount){
           
            $this->provision->update([
                'amount_paid' => $this->amount_paid,
                'received_at' => now()
            ]);

            $amount_remaining = $this->provision->amount - $this->amount_paid;

         
            $contract = Contract::find($this->provision->contract_id);

            $lasting = $contract->provisions()->lasting();

            //Verifica se é a ultima parcela
            if($lasting->id == $this->provision->id){

                switch ($contract->cycle) {
                    case 'daily':
                        $maturity = Carbon::parse($this->provision->maturity)->addDay();
                        break;
                    case 'weekly':
                        $maturity = Carbon::parse($this->provision->maturity)->addWeek();
                        break;
                    case 'fortnightly':
                        $maturity = Carbon::parse($this->provision->maturity)->addWeek(2);
                        break;
                    case 'monthly':
                        $maturity = Carbon::parse($this->provision->maturity)->addMonth();
                        break;
                }

                $this->provision->contract->provisions()->create([
                    'number' => $this->provision->number + 1,
                    'maturity' => $maturity,
                    'amount' => $amount_remaining,
                ]);

            }else{
                $this->provision->contract->provisions
                ->where('id', '>', $this->provision->id)
                ->first()
                ->increment('amount', $amount_remaining);
            }
             
        }


        //Se o valor recebido for maior que o valor da parcela
        if($this->amount_paid > $this->provision->amount){

            $this->provision->update([
                'amount_paid' => $this->provision->amount,
                'received_at' => now()
            ]);


            $provisions = $this->provision->contract->provisions()
            ->whereNull('received_at')
            ->get()
            ->sortByDesc('maturity');

            $paid_out = $this->amount_paid - $this->provision->amount;

            foreach($provisions as $provision){

                if($paid_out >= $this->provision->amount){
                    $provision->update([
                        'amount_paid' => $this->provision->amount,
                        'received_at' => now()
                    ]);
                }

                if($paid_out > 0){

                    $this->provision->contract->provisions()->create([
                        'number' => $provision->number,
                        'maturity' => $provision->maturity,
                        'amount' => $this->provision->amount - $paid_out,
                    ]);

                    $provision->update([
                        'number' => $provision->number + 0.5,
                        'amount_paid' => $paid_out,
                        'received_at' => now()
                    ]);

                }

                $paid_out = $paid_out - $this->provision->amount;
            }

        }
    }
}
