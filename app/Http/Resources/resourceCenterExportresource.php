<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\resourceItemresource;

class resourceCenterExportresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         $resourceOwner = 'N/A';
         $ownerId = 'N/A';
        if ($this->resourceOwnerType == 'Project') {
            $resourceOwner = $this->project->client_name .', '. $this->project->client_code;
            $resourceItems = $this->projectItems;
            $ownerId = $this->project->client_code;

        } else if($this->resourceOwnerType == 'Accommodation') {

            if (isset($this->accommodation->id) & !empty($this->accommodation->id) && $this->accommodation->id != null && $this->accommodation->id != ''){
            $resourceOwner = $this->accommodation->name .', '. $this->accommodation->id;
            $resourceItems = $this->accommodationItems;
            $ownerId = $this->accommodation->id;
           }
        }
        // else if($this->resourceOwnerType == 'Employee'){
        //      if (isset($this->employee->id) & !empty($this->employee->id) && $this->employee->id != null && $this->employee->id != ''){
        //               $resourceOwner = $this->employee->name .', '. $this->employee->emp_id;
        //     $resourceItems = $this->employeeItems;
        //     $ownerId = $this->employee->emp_id;
        //      }
        

        // }
        else{

            $resourceOwner = 'N/A';
            $resourceItems = [];

        }
        
        return [
                'id' => $this->id,
                'ownerType' => $this->resourceOwnerType,
                'resourceOwner' => $resourceOwner,
                'resourceOwnerId' => $ownerId,
                'date' => $this->allocationDate,
                'notes' => $this->notes,
                //'attachment' => null === $this->attachment ? '' : URL($this->attachment),
                //'signature' => null === $this->signature ? '' : $this->signature,
                //'resourceItems' => resourceItemresource::collection($resourceItems),
                
                ];
    }

}
