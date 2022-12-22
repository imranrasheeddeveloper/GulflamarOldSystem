<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class rentPaymentExportResource extends JsonResource
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
                'amount' => $this->amount,
                'period' => date('d/m/Y', strtotime($this->periodFrom)) .' To '. date('d/m/Y', strtotime($this->periodTo)),
                'notes' => $this->notes,
                
                
                
                ];
    }
}
