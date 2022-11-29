<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Models\UserProfile;
use App\Models\Roles\UserRole;
use App\Models\ProfileStatus;
use App\Models\UserSubscription;
use App\Http\Resources\User\ProfileResource;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static function getUserWithDeviceId($id, $name){
        $user = User::where("email",$id)->first();
            if($user == null){
               $user = new User;
               $user->email = $id;
               $user->name = $name;
               $user->password =  Hash::make(\Config::get('constants.system_key'));
               $user->save();
            }else{
                $user->name = $name;
                $user->save();
            }
        return $user;
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class)->select(['user_id','status_id','subscription_id','name','image_url']);
    }

    public function getProfile()
    {
        if($this->profile){
              return new ProfileResource($this->profile);
        }else{
           return new ProfileResource( $this->createDefaultProfile());  
        }
    }

    public function roles()
    {
        return $this->hasMany(UserRole::class)->select(['role_id']);
    }

    function createDefaultProfile(){
         return $this->createProfile("", ProfileStatus::Active, UserSubscription::Free,"");
    }

    function createProfile($name, $status, $subscription, $image_url = ""){
        $profile = new UserProfile;
        $profile->user_id = $this->id;
        $profile->name = $name;
        $profile->status_id = $status;
        $profile->subscription_id = $subscription;
        $profile->image_url = $image_url;
        $profile->save();
        return UserProfile::find($profile->id);
    }

    function isAdmin(){
        if($this->email == env("admin@zorroapp.io")){
            return true;
        }
            return false;
    }





}
