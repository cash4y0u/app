<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\ParcelaResource;

class ContratoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'amount' => $this->amount,
            'amount_provision' => $this->amount_provision,
            'amount_rate' => $this->amount_rate,
            'amount_total' => $this->amount_total,
            'amount_profit' => $this->amount_profit,
            'split' => $this->split,
            'cycle' => $this->cycle,
            'rate' => $this->rate,
            'rate_daily' => $this->rate_daily,
            'maturity' => $this->maturity,
            'status' => $this->status,
            'provisions' => ParcelaResource::collection($this->provisions),
            'created_at' => $this->created_at,
        ];
    }
}
