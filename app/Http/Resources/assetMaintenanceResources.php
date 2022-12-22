<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class assetMaintenanceResources extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'asset_id' =>  assetsMangmentResource::collection($this->asset),
            'maintenance_date'=> $this->maintenance_date,
             'maintenance_type' => $this->maintenance_type,
            'last_reading' => $this->last_reading,
            'attachment' => $this->attachment,
            'notes'=> $this->notes,
            'asset_name'=> $this->asset_name,
            ];
    }
}