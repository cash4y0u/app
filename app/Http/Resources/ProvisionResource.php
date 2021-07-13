<?php

namespace App\Http\Resources;

use Carbon\Carbon;

//use App\Library\Geocode;
use App\Http\Resources\ContractResource;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Provision;
use App\Contract;
use App\Customer;
use App\Console\Commands\GetCoordinates;
use Spatie\Geocoder\Geocoder;

use GuzzleHttp;
use GuzzleHttp\Client;

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

        $yesterday=Carbon::now()->subDays(1)->format('Y-m-d'); /*V01A*/
        $vlrcontract=number_format($this->contract->amount,2,',','.'); /*V01A*/
        $amount=number_format($this->amount, 2, ',', '.'); /*V01A*/
        if($this->contract->created_at->format('Y-m-d')== $yesterday){ /*V01A*/
            $type='Entrega'; /*V01A*/
        }else{ /*V01A*/
            $type='Coleta'; /*V01A*/
        } /*V01A*/

        $idContrato=$this->contract_id; /*  Pegando id do contrato */ /*V01B*/
        $queryContract= Contract::with('customer')->where('id',$idContrato)->get(); /*  Pegando id do usuario a partir do contrato */ /*V01B*/
        $idCustomer=0; /*  Pegando id do usuario a partir do contrato */ /*V01B*/
        foreach($queryContract as $query){  /*V01B*/
        $idCustomer=$query->customer_id;  /*V01B*/
        }  /*V01B*/
        $endereco="";   /*V01B*/
        $result = Customer::where('id',$idCustomer)->first();  /*  Pegando enderecos  a partir do id do usuario */ /*V01B*/
         /*V01B*/
         foreach($result as $results){ 
           $endereco=$result->adresses; 
        }/*V01B*/

        $numerodeenderecos = count($endereco); /*  Vendo quantos endereços estão cadastrados */ /*V01B*/
        $enderecofavorito="";/*V01B*/

        for($i=0;$i<$numerodeenderecos;$i++){/*V01B*/
            if($endereco[$i]['favorite']=='true'){/*V01B*/
               $enderecofavorito=$endereco[$i]; /*V01B*/ /*  Pegando o endereço favorito */ /*V01B*/
            }/*V01B*/
        }/*V01B*/
        /* Criando endereço para jogar na API GOOGLE GEOCODING*/ /*V01B*/
        $enderecodistancia=$enderecofavorito['street'] .", ". $enderecofavorito['number'] ." - ". $enderecofavorito['district']." - ". $enderecofavorito['city']; /*V01B*/
        /* Os procedimentos abaixo são referentes ao json da api geocoding google*/
        $client = new Client();  /*V01B*/
        $geocoder = new Geocoder($client);  /*V01B*/
        $geocoder->setApiKey(config('geocoder.key','AIzaSyDvy19luj-x277RI_bQ9J0lekoQcCshaek'));  /*V01B*/
        $geocodes = $geocoder->getCoordinatesForAddress($enderecodistancia);  /*V01B*/
        $latend= $geocodes['lat'];  /*V01B*/
        $lngend=$geocodes['lng'];
        /* Procedimentos para calculo de distancia */
        $latbase = -23.508603373818936;
        $lngbase = -46.45354021401643;
        $theta = $lngend - $lngbase;
        $radianos = sin(deg2rad($latend)) * sin(deg2rad($latbase)) +  cos(deg2rad($latend)) * cos(deg2rad($latbase)) * cos(deg2rad($theta));
        $radianos = acos($radianos);
        $radianos = rad2deg($radianos);
        $miles = $radianos * 60 * 1.1515;
        $distancia=$miles * 1.609344;
        $distancia= number_format($distancia, 2, '.', '');
    
      
          
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
            'type'=> $type, //*V01A*
            'valuecoletaentrega'=> ($type=='Entrega'? $vlrcontract : $amount), //*V01A*
            'distancia'=> $distancia
        ];
    }
}
