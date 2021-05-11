<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;
use App\Http\Resources\ContratoResource;

class ClienteResource extends JsonResource
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
            'name' => $this->name,
            'birth' => $this->birth,
            'document' => $this->document,
            'email' => $this->email,
            'phone' => $this->phone,
            'adresses' => $this->adresses,
            'photo' => "https://cash4you.app{$this->photo}",
            'contracts' => ContratoResource::collection($this->contracts),
            'created_at' => $this->created_at,
        ];
    }
}
