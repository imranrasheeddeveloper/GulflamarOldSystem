<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class invoiceServiceResource extends JsonResource
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
                'name' => $this->expenseitem ? $this->expenseitem->name : '',
                'quantity' => $this->quantity,
                'amount' => $this->amount,
                'grandTotal' => $this->quantity * $this->amount,
                
                ];
    }
}
