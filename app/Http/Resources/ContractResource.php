<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;
use App\Http\Resources\ProvisionResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch ($this->cycle) {
            case 'daily':
                $cycle = 'diário';
                break;
            case 'weekly':
                $cycle = 'semanal';
                break;
            case 'fortnightly':
                $cycle = 'quinzenal';
                break;
            case 'monthly':
                $cycle = 'mensal';
                break;
        }
        return [
            'id' => $this->id,
            'customer' => $this->customer,
            'amount' => number_format($this->amount, 2, ',', '.'),
            'amount_provision' => number_format($this->amount_provision, 2, ',', '.'),
            'amount_rate' => number_format($this->amount_rate, 2, ',', '.'),
            'amount_total' => number_format($this->amount_total, 2, ',', '.'),
            'amount_profit' => number_format($this->amount_profit, 2, ',', '.'),
            'maturity' => Carbon::parse($this->maturity)->format('d/m/Y'),
            'split' => $this->split,
            'cycle' => $cycle,
            'rate' => $this->rate,
            'status' => ($this->status == 'open' ? 'Aberto' : 'Quitado'),
            'created_at' => $this->created_at->format('d/m/Y'),
            'diffForHumans' => $this->created_at->diffForHumans(),
        ];
    }
}
