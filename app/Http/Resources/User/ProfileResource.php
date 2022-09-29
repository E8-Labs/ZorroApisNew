<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
             'user_id' => $this->user_id,
             'name' =>  $this->name,
             'image_url' => \Config::get('constants.profile_images')  . $this->image_url,
             'status_id' => (int)$this->status_id,
             'subscription_id' => (int)$this->subscription_id,
             'email' => $this->user->email,
             'roles' => $this->user->roles          
         ];
    }
}
