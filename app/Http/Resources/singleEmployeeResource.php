<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\selectGeneralresource;
use App\Http\Resources\selectClientresource;
use App\Http\Resources\selectVendorresource;
use App\Http\Resources\selectProfessionresource;

class singleEmployeeResource extends JsonResource
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
                'name' => $this->name,
                'emp_id' => $this->emp_id,
                'nationality' => $this->nationality,
                'religion' => $this->religion,
                'dob' => $this->dob,
                'joining_date' => $this->joining_date,
                'age' => $this->age,
                'contact_number'=> $this->contact_number,
                'emp_photo' => $this->emp_photo ? URL($this->emp_photo) : '',
                'benefits'=> $this->benefits,

                'iban' => $this->iban,
                'vacation_date' => $this->vacation_date,
                'notes' => $this->notes,
                'iqama_no' => $this->iqama_no,
                'iqama_expiry_date' => $this->iqama_expiry_date,
                'iqama_profession' =>  new selectProfessionresource($this->profession_bases) ?? '',
                'iqama'=> $this->iqama ? URL($this->iqama) : '',
                'passport_number' => $this->passport_number,
                'passport_expiry_date'=> $this->passport_expiry_date,

                'passport' => $this->passport ? URL($this->passport) : '',
                'passport_2' => $this->passport_2 ? URL($this->passport_2) : '',
                'ajeer' => $this->ajeer ? URL($this->ajeer) : '',
                'ajeer_expiration_date' => $this->ajeer_expiration_date,
                'insurance_card' => $this->insurance_card ? URL($this->insurance_card) : '',
                'insurance_card_expirationDate' => $this->insurance_card_expirationDate,
                'vendor'=> new selectVendorresource($this->vendors) ?? '',
                'salary_rate' => $this->salary_rate,
                'client'=> new selectClientresource($this->clients) ?? '',
                'client_location'=> $this->client_location ?? '',

                'status' => $this->status,
                'accommodation' => new selectGeneralresource($this->accommodations) ?? '',
                'contract_start' => $this->contract_start,
                'project_stop_date' => $this->project_stop_date,
                'lang_eng' => $this->lang_eng,
                'lang_ar' => $this->lang_ar,
                'lang_hind'=> $this->lang_hind,
                'appearance_presentable' => $this->appearance_presentable,
                'apprearance_beard'=> $this->apprearance_beard,
                'skills'=> explode(',', $this->skills),
                'misconduct'=> $this->misconduct,
                'rating'=> $this->rating,
                
                ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => 'Details fetched',
        ];
    }
}
