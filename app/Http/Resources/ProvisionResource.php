<?php

namespace App\Http\Resources;

use Carbon\Carbon;

use App\Library\Geocode;
use App\Http\Resources\ContractResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProvisionResource extends JsonResource
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
        $fine = ($this->contract->rate_daily / 100) * $this->amount * now()->endOfDay()->diffInDays($maturity);

        $total_balance = round($this->contract->provisions->where('status', 'pending')->sum('amount'));

        /*V01A*/
        $yesterday=Carbon::now()->subDays(1)->format('Y-m-d');
        $vlrcontract=number_format($this->contract->amount,2,',','.');
        $amount=number_format($this->amount, 2, ',', '.');
        if($this->contract->created_at->format('Y-m-d')== $yesterday){
            $type='Entrega';
        }else{
            $type='Coleta';
        }

        return [
            'id' => $this->id,
            'number' => $this->number,
            'amount' => number_format($this->amount, 2, ',', '.'),
            'amount_paid' => number_format($this->amount_paid, 2, ',', '.'),
            'maturity' => $maturity->format('d/m/Y'),
            'maturity2' => $maturity->formatLocalized('%d de %B %Y'),
            'dayOfWeek' => $maturity->formatLocalized('%A'),
            'status' => ($this->status == 'paid' ? 'Pago' : 'Pendente'),
            'minimum_payment' => ($this->amount_paid == $this->contract->amount_rate),
            'paid_at' => Carbon::parse($this->paid_at)->format('d/m/Y H:i'),
            'received_at' => Carbon::parse($this->received_at)->format('H:i'),
            'isReceived' => !is_null($this->received_at),
            'isExpired' => $maturity->lessThanOrEqualTo(now()->endOfDay()),
            'amountFine' => number_format($fine, 2, ',', '.'),
            'diffInDays' => now()->endOfDay()->diffInDays($maturity),
            'diffForHumans' => $maturity->diffForHumans(),
            'total_balance' => $total_balance,
            'coordinates' => $this->coordinates,
            'contract' => new ContractResource($this->contract),
            'type'=> $type, //*VO1A*
            'valuecoletaentrega'=> ($type=='Entrega'? $vlrcontract : $amount), //*VO1A*
        ];
    }
}
