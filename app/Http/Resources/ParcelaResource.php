<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParcelaResource extends JsonResource
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
            'number' => $this->number,
            'amount' => $this->amount,
            'amount_paid' => $this->amount_paid,
            'maturity' => $this->maturity->format('Y-m-d'),
            'status' => $this->status,
            'paid_at' => $this->paid_at,
            'received_at' => $this->received_at,
            'created_at' => $this->created_at,
        ];
    }
}
