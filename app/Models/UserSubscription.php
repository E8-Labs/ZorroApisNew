<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    const Free = 1;
    const Three_Months = 2;
    const Six_Months = 3;
    const One_Year = 4;


    static function isVaild($id){
    	$subscription = UserSubscription::find($id);
    	if($subscription == null){
    		return false;
    	}
    	return true;
    }
}
