<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\resourceItemresource;

class resourceCenterresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resourceOwnerType == 'Project') {


            // $resourceOwner = $this->project->client_name .', '. $this->project->client_code;
            if(isset($this->project->client_name) && isset($this->project->client_code)){
                $resourceOwner = $this->project->client_name . ' , '. $this->project->client_code;
            }else{
                $resourceOwner = '';
            }

            // $resourceItems = $this->projectItems;
            if(isset($this->projectItems)){
                $resourceItems = $this->projectItems;
            }else{
                $resourceItems = '';
            }

            // $ownerId = $this->project->client_code;
            if(isset($this->project->client_code)){
                $ownerId = $this->project->client_code;
            }else{
                $ownerId = '';
            }

        } else if($this->resourceOwnerType == 'Accommodation') {

            // $resourceOwner = $this->accommodation->name .', '. $this->accommodation->id;
            if(isset($this->accommodation->name) && isset($this->accommodation->id)){
                $resourceOwner = $this->accommodation->name . ' , '. $this->accommodation->id;
            }else{
                $resourceOwner = '';
            }
            // $resourceItems = $this->accommodationItems;
            if(isset($this->accommodationItems)){
                $resourceItems = $this->accommodationItems;
            }else{
                $resourceItems = '';
            }
            // $ownerId = $this->accommodation->id;
            if(isset($this->accommodation->id)){
                $ownerId = $this->accommodation->id;
            }else{
                $ownerId = '';
            }
            
        } else if($this->resourceOwnerType == 'Employee'){

            // $resourceOwner = $this->employee->name .', '. $this->employee->emp_id;
            if(isset($this->employee->name) && isset($this->employee->emp_id)){
                $resourceOwner = $this->employee->name . ' , '. $this->employee->emp_id;
            }else{
                $resourceOwner = '';
            }
            // $resourceItems = $this->employeeItems;
            if(isset($this->employeeItems)){
                $resourceItems = $this->employeeItems;
            }else{
                $resourceItems = '';
            }
            // $ownerId = $this->employee->emp_id;
            if(isset($this->employee->emp_id)){
                $ownerId = $this->employee->emp_id;
            }else{
                $ownerId = '';
            }

        }

        else{

            $resourceOwner = 'N/A';
            $resourceItems = [];

        }
        
        return [
                'id' => $this->id,
                'ownerType' => $this->resourceOwnerType,
                'resourceOwner' => $resourceOwner,
                'resourceOwnerId' => $ownerId,
                'resource_owner_location' => $this->resource_owner_location,
                'date' => $this->allocationDate,
                'notes' => $this->notes,
                'attachment' => null === $this->attachment ? '' : URL($this->attachment),
                'signature' => null === $this->signature ? '' : $this->signature,
                'created_by' => $this->created_by,
                'updated_by' => $this->updated_by,
                 
                'resourceItems' => resourceItemresource::collection($resourceItems),
                
                ];
    }
}
