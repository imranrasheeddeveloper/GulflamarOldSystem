<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class clientServiceResource extends JsonResource
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
                'period' => $this->period,
                'rate' => $this->rate,
                
                ];
    }
}
