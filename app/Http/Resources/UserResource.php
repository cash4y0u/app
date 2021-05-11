<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;
use App\Http\Resources\UserResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $administrators = ['contatotiagobezerra@icloud.com','gustavo@protectsite.com.br'];

        return [
            'email' => $this->email,
            'api_token' => $this->api_token,
            'isAdmin' => (in_array($this->email, $administrators)),
            'name' => $this->name,
            'picture' => $this->picture
        ];
    }
}
