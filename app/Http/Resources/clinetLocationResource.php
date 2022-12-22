<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class clinetLocationResource extends JsonResource
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
         'id' => $this->id ,
         'location_name' => $this->location_name ,
         'latitude' => $this->latitude,
         'longitude'=> $this->longitude ,
         'location_map'=> $this->location_map,
        ];
    }
}
