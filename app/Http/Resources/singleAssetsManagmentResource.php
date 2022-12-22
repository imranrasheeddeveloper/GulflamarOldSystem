<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class singleAssetsManagmentResource extends JsonResource
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
            'asset_name'=> $this->asset_name,
            'asset_type'=> new assetTypeResources($this->assetType),
            'year'=> $this->year,
            'model'=> new assetTypeResources($this->assetModel),
            'make'=> new assetTypeResources($this->assetMake),
            'capacity'=> new assetTypeResources($this->assetCapacity),
            'fuel_type'=> $this->fuel_type,
            'chassis_number'=> $this->chassis_number,
            'legal_documents'=> $this->legal_documents,
            'mileage' => $this->mileage,
            'notes' => $this->notes,
            'created_by'=> $this->created_by,
            'updated_by'=> $this->updated_by,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            'attachment'=> $this->attachment
            ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => 'Details fetched',
            'base_url' => url('/')
        ];
    }
}
