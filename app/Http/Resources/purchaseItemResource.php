<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\accommodationResource;
use App\Models\projectResource;
use App\Models\employeeResource;
use App\Models\employeeResource AS employeeItemResource;

class purchaseItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->itemId != null && $this->itemType != null)
        {
            if($this->itemType == 'project')
            {
                $product = projectResource::find($this->itemId);
                if($product)
                {
                    $name = $product->name;
                }
                else
                {
                    $name = '';
                }
            }
            else if($this->itemType == 'accommodation')
            {
                $product = accommodationResource::find($this->itemId);
                if($product)
                {
                    $name = $product->name;
                }
                else
                {
                    $name = '';
                }
            }
            else if($this->itemType == 'employee')
            {
                $product = employeeItemResource::find($this->itemId);
                if($product)
                {
                    $name = $product->name;
                }
                else
                {
                    $name = '';
                }
            }
            else
            {
                $name = '';
            }
        }
        else
        {
            $name = $this->name;
        }
        return [
                'serviceName' => $name,
                'name' => $name,
                'quantity' => $this->quantity,
                'rate' => $this->rate,
                'total' => $this->total,
                
                ];
    }
}
