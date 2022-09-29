<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileFreeOrPremiumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if( $this->profile != null ){
        return [
             'name' =>  $this->profile->name,
             'image_url' => \Config::get('constants.profile_images')  . $this->profile->image_url,
             'user_id' => $this->id,
             'subscription_id' => 1 ,// (int)$this->subscription_id,
             "subscription_expiry" => $this->created_at,
        "email" => $this->email
         ];
     }else{
        return [
             'name' =>  $this->name,
             'image_url' => "",
              'user_id' => $this->id,
              'subscription_id' => 1 ,// (int)$this->subscription_id,
              "subscription_expiry" => $this->created_at,
        "email" => $this->email
         ];
     }
    }
}
