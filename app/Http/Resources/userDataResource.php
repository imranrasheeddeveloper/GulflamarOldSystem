<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class userDataResource extends JsonResource
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
                'name' => $this->name,
                'email' => $this->email,
                'user_name' => $this->user_name,
                'role' => $this->userLevel->name,
                'role_id' => $this->userLevel ? json_decode(json_encode(['value'=>$this->userLevel->id,'text'=>$this->userLevel->name], JSON_FORCE_OBJECT)) : '',
                'fullName'=> $this->name,
                'mobile'=> $this->mobile,
                'pin'=> $this->pin,
                /*'profile_pic' => url($this->profile_pic),*/
                
                ];
    }
}
