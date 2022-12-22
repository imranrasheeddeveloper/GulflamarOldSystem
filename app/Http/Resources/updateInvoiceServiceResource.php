<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class updateInvoiceServiceResource extends JsonResource
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
                'id' => $this->invoice_exp_id,
                'name' => $this->expenseitem ? json_decode(json_encode(['value'=>$this->expenseitem->id,'text'=>$this->expenseitem->name], JSON_FORCE_OBJECT)) : '',
                'quantity' => $this->quantity,
                'amount' => $this->amount,
                
                ];
    }
}
