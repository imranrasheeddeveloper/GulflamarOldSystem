<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class userResource extends JsonResource
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
                'user_id' => $this->id,
                'token' => $this->token,
                'name' => $this->name,
                'email' => $this->email,
                'user_name' => $this->user_name,
                'role' => $this->userLevel->name,
                'fullName'=> $this->name,
                'username' => $this->user_name,
                'ability'=> $this->ability,
                /*'profile_pic' => url($this->profile_pic),*/
                
                ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => 'Logged in Succesfully',
        ];
    }
}
