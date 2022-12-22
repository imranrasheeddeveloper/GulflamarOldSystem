<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class billPaymentExportResource extends JsonResource
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
                'accommodation' => $this->accommodations->name .', '. $this->accommodations->id,
                'date' => $this->date,
                'billType' => $this->billType,
                'amount' => $this->amount,
                'period' => $this->billMonth,
                'notes' => $this->notes,
                
                
                
                ];
    }
}
