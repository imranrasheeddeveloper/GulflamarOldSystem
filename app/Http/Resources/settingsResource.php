<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class settingsResource extends JsonResource
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
                'vat' => $this->vat,
                
                ];
    }
}
