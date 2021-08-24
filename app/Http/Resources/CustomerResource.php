<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;
use App\Http\Resources\ContractResource;

class CustomerResource extends JsonResource
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
            'id' => $this->id,
            'defaultAddress' => $this->adresses,
            'name' => $this->name,
            'birth' => $this->birth,
            //'birth' => Carbon::parse($this->birth)->format('d/m/Y'),
            'document' => $this->document,
            'email' => $this->email,
            'phone' => $this->phone,
            'adresses' => $this->adresses,
            'photo' => "https://cash4you.app/{$this->photo}",
            'total_contracts' => $this->contracts->count(),
            'contracts' => ContractResource::collection($this->contracts),
            'age' => Carbon::parse($this->birth)->longAbsoluteDiffForHumans(),
        ];
    }
}
