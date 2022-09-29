<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;
use App\Models\UserProfile;
use App\Models\UserSubscription;
use App\Http\Traits\FileTrait;

class UserController extends ParentController
{
    use FileTrait;

    const PROFILE = "profile";
    const ROLES = "roles";
    const UPLOADED_FILE_NAME = "image";

    public function profile(Request $request)
    {

          $user = Auth::user();
          $data = [ self::PROFILE => $user->getProfile()];
          return response()->json([ $this->DATA  => $data , $this->SUCCESS => true, $this->MSG  => ""], 200);
    }

    public function updateProfile(Request $request)
    { //file
          $validator = Validator::make($request->all(), ['name'=>'required', 'subscription_id'=>'required', 'image'=>'image|mimes:jpeg,jpg,png' ]);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
            $name =  $request['name'];
            $subscriptionId =  $request['subscription_id'];
            if(!UserSubscription::isVaild($subscriptionId)){
                return response()->json([$this->MSG  => "Invalid Subscription" ,$this->SUCCESS => false], 200);
            }

            $profile = UserProfile::where("user_id",Auth::user()->id )->first();  ///Auth::user()->profile;
            $profile->name = $name;
            $profile->subscription_id = $subscriptionId;
            /////////////////////////////////////////////////   SAVE PORFILE IMAGE //////////////
            $fileName = $this->saveImageFile($request, self::UPLOADED_FILE_NAME, \Config::get('constants.profile_images_save'), $profile->image_url);
                   if($fileName != false){
                      $profile->image_url = $fileName; 
                    }
            $profile->save();
            $data = [ self::PROFILE => Auth::user()->getProfile()];
            return response()->json([$this->DATA => $data , $this->SUCCESS => true, $this->MSG => $this->UPATED_SUCCESSFULLY], 200);
          }
    }

    
}
