<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\rentPaymentResource;

use Carbon\Carbon;

class accommodationExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $myDate = $this->getAttribute('due-date');

        if($myDate)
        {
            $duedate = Carbon::createFromFormat('Y-m-d', $myDate);

            if ($duedate->isToday()) {
                
                $dueDateVariant = 'danger';

            } else if($duedate->isPast()) {
                $dueDateVariant = 'danger';

            } else if(strtotime($myDate) > strtotime('+7 day')) {
                $dueDateVariant = 'success';
            }
            else
            {
                $dueDateVariant = 'warning';
            }
            
        }
        else
        {
            $dueDateVariant = 'light';
        }

        $dateWithVariant = [];
        $dateWithVariant['dueDate'] = $this->getAttribute('due-date');
        $dateWithVariant['dueDateVariant'] = $dueDateVariant;

        return [
                'id' => $this->id,
                /*'orderNo' => $this->orderNo,*/
                'name' => $this->name,
                'location' => $this->location,
                'address' => $this->address,
                'rooms' => $this->rooms,
                'beds' => $this->beds,
                'contract_startdate' => $this->contract_startdate,
                'contract_enddate' => $this->contract_enddate,
                'rent' => $this->rent,
                'services' =>  $this->services,
                'dueDate' => $this->getAttribute('due-date'),
                'period' => $this->period,
                'apartment' => $this->apartment,
                'electricityAccountNo' => $this->electricityAccountNo,
                'waterBillAccountNo' => $this->waterBillAccountNo,
                //'rentPayments' => [],
                //'dueDateVariant' => $dueDateVariant,
                //'dateWithVariant' => $dateWithVariant,

                'contactName' => $this->contactName,
                'contactNo' => $this->contactNo,
                'accountName' => $this->accountName,
                'bankName' => $this->bankName,
                'iban' => $this->iban,
                'fixedElectricityAmount' => $this->fixedElectricityAmount,
                'fixedWaterAmount' => $this->fixedWaterAmount,
                /*'rentPayments' => rentPaymentResource::collection($this->payments),*/
                
                ];
    }
}
