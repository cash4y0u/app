<?php

namespace App\Http\Resources;

use Carbon\Carbon;

use App\Library\Geocode;
use App\Http\Resources\ContractResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProvisionPendingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $maturity = Carbon::parse($this->maturity)->endOfDay();


        return [
            'id' => $this->id,
            'number' => $this->number,
            'amount' => number_format($this->amount, 2, ',', '.'),
            'maturity' => $maturity->format('d/m/Y'),
            'dayOfWeek' => $maturity->formatLocalized('%A'),
            'status' => ($this->status == 'paid' ? 'Pago' : 'Pendente'),
            'isExpired' => $maturity->lessThanOrEqualTo(now()->endOfDay()),
            'diffForHumans' => $maturity->diffForHumans(),
            'customer' => $this->contract->customer,
        ];
    }
}
