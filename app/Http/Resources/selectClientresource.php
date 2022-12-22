<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class selectClientresource extends JsonResource
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
                'value' => $this->client_code,
                'text' => $this->client_name
                
                ];
    }
}
