<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class billPaymentResource extends JsonResource
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
                'accommodation_base_id' => $this->accommodations->id,
                'date' => $this->date,
                'billType' => $this->billType,
                'amount' => $this->amount,
                'period' => $this->billMonth,
                'notes' => $this->notes,
                'attachment' => null === $this->attachment ? '' : URL($this->attachment),
                'accommodation' => $this->accommodations->name .', '. $this->accommodations->id,
                'created_by'=> $this->created_by,
                'updated_by'=> $this->updated_by,
                
                ];
    }
}
