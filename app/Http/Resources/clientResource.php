<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\clientServiceResource;
use App\Http\Resources\clinetLocationResource;
use App\Models\ClientLocation;

class clientResource extends JsonResource
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
                'client_name' => $this->client_name,
                'client_name_ar' => $this->client_name_ar,
                'client_code' => $this->client_code,
                'address' => $this->address,
                'cr_no' => $this->cr_no,
                'vat_no' => $this->vat_no,
                'cr' =>  null === $this->cr ? '' : URL($this->cr),
                'vat_cert' =>  null === $this->vat_cert ? '' : URL($this->vat_cert),
                'ajeer_license' => $this->ajeer_license,
                'dept_en' => $this->dept_en,
                'dept_ar' => $this->dept_ar,
                'contract' =>  null === $this->contract ? '' : URL($this->contract),
                'contract_type' => $this->contract_type,
                'contract_status' => $this->contract_status,
                'fat_details' => explode(',', $this->fat_details),
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'daysWeek' => $this->getAttribute('days-week'),
                'hoursDay' => $this->getAttribute('hours-day'),
                'services'=> clientServiceResource::collection($this->clientServices),
                'location' => clinetLocationResource::collection($this->clinetLocation),
                'contactName' => $this->contactName,
                'contactNo' => $this->contactNo,
                'account_name' => $this->account_name,
                'bank_name' => $this->bank_name,
                'iban' => $this->iban,
                'created_by'=> $this->created_by,
                'updated_by'=> $this->updated_by,
                
                ];
    }
}
