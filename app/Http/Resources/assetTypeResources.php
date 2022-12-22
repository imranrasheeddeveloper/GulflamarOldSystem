<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class assetTypeResources extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title'=> $this->title,
            'created_by'=> $this->created_by,
            'updated_by'=> $this->updated_by,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            ];
    }
    
}