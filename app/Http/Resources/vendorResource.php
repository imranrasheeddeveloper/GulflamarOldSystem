<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\vendorServiceResource;

class vendorResource extends JsonResource
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
                'code' => $this->code,
                'name' => $this->name,
                'name_ar' => $this->name_ar,
                'cr_no' => $this->cr_no,
                'cr_file' =>  null === $this->cr_file ? '' : URL($this->cr_file),
                'vat_no' => $this->vat_no,
                'vat_file' =>  null === $this->vat_file ? '' : URL($this->vat_file),
                'contact_ops' => $this->contact_ops,
                'contact_ops_no' => $this->contact_ops_no,
                'contact_billing' => $this->contact_billing,
                'contact_billing_no' => $this->contact_billing_no,
                'contact_gov' => $this->contact_gov,
                'contact_gov_no' => $this->contact_gov_no,
                'contract' =>  null === $this->contract ? '' : URL($this->contract),
                'services'=> vendorServiceResource::collection($this->vendorServices),
                /*'rentPayments' => rentPaymentResource::collection($this->payments),*/
                
                ];
    }
}
