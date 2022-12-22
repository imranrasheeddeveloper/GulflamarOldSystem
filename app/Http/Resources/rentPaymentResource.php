<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class rentPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $accommodationFullname = '';
        if($this->accommodations)
        {
            $accommodationFullname = $this->accommodations->name .', '. $this->accommodations->id;
        }
        return [
                'id' => $this->id,
                'accommodation_base_id' => $this->accommodations->id ?? '',
                'accommodation' => $this->accommodations->name ?? '',
                'date' => $this->date,
                'amount' => $this->amount,
                'period' => date('d/m/Y', strtotime($this->periodFrom)) .' To '. date('d/m/Y', strtotime($this->periodTo)),
                'notes' => $this->notes,
                'periodFrom' => $this->periodFrom,
                'periodTo' => $this->periodTo,
                'attachment' => null === $this->attachment ? '' : URL($this->attachment),
                'rent_reciept' => null === $this->rent_reciept ? '' : URL($this->rent_reciept),
                'accommodation' => $accommodationFullname,
                'created_by'=> $this->created_by,
                'updated_by'=> $this->updated_by,

                
                ];
    }
}
