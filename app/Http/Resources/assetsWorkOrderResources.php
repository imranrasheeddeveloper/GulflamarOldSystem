<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class assetsWorkOrderResources extends JsonResource
{
    
    public function toArray($request)
    {
       
        return [
            'id' => $this->id,
            'asset_id' =>  assetsMangmentResource::collection($this->asset),
            'fromDate'=> $this->fromDate,
            'toDate' => $this->toDate,
            'c_id'=> clientResource::collection($this->client),
            'rate_basis' => $this->rate_basis,
            'rate'=> $this->rate,
            'check_out_reading'=> $this->check_out_reading,
            'check_in_reading'=> $this->check_in_reading,
            'scope'=> $this->scope,
            'notes'=> $this->notes,
            'asset_name'=> $this->asset_name,
            'location_name'=> $this->location_name,
            'client_name'=> $this->client_name,
            'created_by'=> $this->created_by,
            'updated_by'=> $this->updated_by, 
            'location_id' => clinetLocationResource::collection($this->location),
            ];
    }
}