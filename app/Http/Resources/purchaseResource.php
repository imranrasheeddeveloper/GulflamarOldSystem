<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\purchaseItemResource;

class purchaseResource extends JsonResource
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
                'purchaseOrder' =>null === $this->purchaseOrderFile ? '' : URL($this->purchaseOrderFile),
                'purchaseType' => $this->purchaseType,
                'Total' => $this->grandTotal,
                'date' => $this->date,
                'subtotal' => $this->subtotal,
                'vat' => $this->vat,

                'vat_not_vat' => $this->vat_not_vat,
                'seller_type' => $this->seller_type,
                'request_seller' => $this->request_seller,
                'request_seller_id' => $this->request_seller_id,


                'net_total' => $this->net_total,
                'resourceItems' => purchaseItemResource::collection($this->purchasedItems),
                'created_by' => $this->created_by,
                'updated_by' => $this->updated_by,

                
                ];
    }
}
