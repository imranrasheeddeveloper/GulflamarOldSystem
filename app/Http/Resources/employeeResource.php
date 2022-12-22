<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class employeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $myDate = $this->iqama_expiry_date;

        if($myDate)
        {
            $duedate = Carbon::createFromFormat('Y-m-d', $myDate);

            if ($duedate->isToday()) {
                
                $dueDateVariant = 'danger';

            } else if($duedate->isPast()) {
                $dueDateVariant = 'danger';

            } else if(strtotime($myDate) > strtotime('+30 day')) {
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

        $iqamaExpireVarient = [];
        $iqamaExpireVarient['iqama_expiry_date'] = $this->iqama_expiry_date;
        $iqamaExpireVarient['dateVariant'] = $dueDateVariant;

        $myDate = $this->passport_expiry_date;

        if($myDate)
        {
            $duedate = Carbon::createFromFormat('Y-m-d', $myDate);

            if ($duedate->isToday()) {
                
                $dueDateVariant = 'danger';

            } else if($duedate->isPast()) {
                $dueDateVariant = 'danger';

            } else if(strtotime($myDate) > strtotime('+30 day')) {
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

        $passportExpireVarient = [];
        $passportExpireVarient['passport_expiry_date'] = $this->passport_expiry_date;
        $passportExpireVarient['dateVariant'] = $dueDateVariant;


        $myDate = $this->ajeer_expiration_date;

        if($myDate)
        {
            $duedate = Carbon::createFromFormat('Y-m-d', $myDate);

            if ($duedate->isToday()) {
                
                $dueDateVariant = 'danger';

            } else if($duedate->isPast()) {
                $dueDateVariant = 'danger';

            } else if(strtotime($myDate) > strtotime('+30 day')) {
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

        $ajeerExpireVarient = [];
        $ajeerExpireVarient['ajeer_expiration_date'] = $this->ajeer_expiration_date;
        $ajeerExpireVarient['dateVariant'] = $dueDateVariant;

        $myDate = $this->insurance_card_expirationDate;

        if($myDate)
        {
            $duedate = Carbon::createFromFormat('Y-m-d', $myDate);

            if ($duedate->isToday()) {
                
                $dueDateVariant = 'danger';

            } else if($duedate->isPast()) {
                $dueDateVariant = 'danger';

            } else if(strtotime($myDate) > strtotime('+30 day')) {
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

        $insuranceExpireVarient = [];
        $insuranceExpireVarient['insurance_card_expirationDate'] = $this->insurance_card_expirationDate;
        $insuranceExpireVarient['dateVariant'] = $dueDateVariant;

         return [
                'name' => $this->name,
                'emp_id' => $this->emp_id,
                'nationality' => $this->nationality,
                'religion' => $this->religion,
                'dob' => $this->dob,
                'joining_date' => $this->joining_date,
                'age' => $this->age,
                'contact_number'=> $this->contact_number,
                'emp_photo' => $this->emp_photo ? URL($this->emp_photo) : '',
                'benefits'=> $this->benefits,

                'iban' => $this->iban,
                'vacation_date' => $this->vacation_date,
                'notes' => $this->notes,
                'iqama_no' => $this->iqama_no,
                'iqama_expiry_date' => $this->iqama_expiry_date,
                'iqama_profession' => $this->iqama_profession,
                'iqama'=> $this->iqama ? URL($this->iqama) : '',
                'passport_number' => $this->passport_number,
                'passport_expiry_date'=> $this->passport_expiry_date,

                'passport' => $this->passport ? URL($this->passport) : '',
                'passport_2' => $this->passport_2 ? URL($this->passport_2) : '',
                'ajeer' => $this->ajeer ? URL($this->ajeer) : '',
                'ajeer_expiration_date' => $this->ajeer_expiration_date,
                'insurance_card' => $this->insurance_card ? URL($this->insurance_card) : '',
                'insurance_card_expirationDate' => $this->insurance_card_expirationDate,
                'vendor'=> $this->vendors->code ?? '',
                'salary_rate' => $this->salary_rate,
                'client'=> $this->clients->client_name ?? '',
                'client_location'=> $this->client_location ?? '',
                'clientCode'=> $this->clients->client_code ?? '',

                'status' => $this->status,
                'accommodation' => $this->accommodations->name ?? '',
                'contract_start' => $this->contract_start,
                'project_stop_date' => $this->project_stop_date,
                'lang_eng' => $this->lang_eng,
                'lang_ar' => $this->lang_ar,
                'lang_hind'=> $this->lang_hind,
                'appearance_presentable' => $this->appearance_presentable,
                'apprearance_beard'=> $this->apprearance_beard,
                'skills'=> explode(',', $this->skills),
                'misconduct'=> $this->misconduct,
                'rating'=> $this->rating,

                'iqamaExpireVarient'=> $iqamaExpireVarient,
                'passportExpireVarient'=> $passportExpireVarient,
                'ajeerExpireVarient'=> $ajeerExpireVarient,
                'insuranceExpireVarient'=> $insuranceExpireVarient,
                'created_by'=> $this->created_by,
                'updated_by'=> $this->updated_by,


                
                ];
    }
}
