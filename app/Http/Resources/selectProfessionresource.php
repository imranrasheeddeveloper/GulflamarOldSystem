<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class selectProfessionresource extends JsonResource
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
                'value' => $this->profession_en,
                'text' => $this->profession_en.'-'.$this->profession_ar
                
                ];
    }
}
