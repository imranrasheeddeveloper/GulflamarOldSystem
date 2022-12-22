<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class purchaseExportResource extends JsonResource
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
                /*'orderNo' => $this->orderNo,*/
               
                'purchaseType' => $this->purchaseType,
                //'Total' => $this->grandTotal,
                'date' => $this->date,
                'subtotal' => $this->subtotal,
                'vat' => $this->vat,
                'net_total' => $this->net_total,
                'seller_type' => $this->seller_type,
                'request_seller_id' => $this->request_seller_id,
                'request_seller' => $this->request_seller,

                
                
                ];
    }
}
