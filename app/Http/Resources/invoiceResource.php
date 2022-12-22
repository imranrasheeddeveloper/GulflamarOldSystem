<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\invoiceServiceResource;

class invoiceResource extends JsonResource
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
                'invoice_number' => $this->invoice_number,
                'invoice_date' => $this->invoice_date,
                'status' => $this->status,
                'client_code' => $this->client_code,
                'client' => $this->client ? $this->client->client_name : '',
                'location_en' => $this->location_en,
                'period' => $this->period ?? '',
                'invoice_copy' =>  null === $this->invoice_copy ? '' : URL($this->invoice_copy),
                'subtotal' => $this->subtotal,
                'vat' => $this->vat,
                'net_total' => $this->net_total,
                'services'=> invoiceServiceResource::collection($this->invoiceExpences),
                'created_by'=> $this->created_by,
                'updated_by'=> $this->updated_by,
                
                ];
    }
}
