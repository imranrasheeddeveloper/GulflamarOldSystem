<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class userSignupResource extends JsonResource
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
                'user_name' => $this->user_name,
                'email' => $this->email,
                /*'profile_pic' => url($this->profile_pic),*/
                ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => 'Registered Succesfully',
        ];
    }
}

