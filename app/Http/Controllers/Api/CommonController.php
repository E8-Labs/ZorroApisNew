<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\County;

use App\Models\Types\LoanUseType;
use App\Models\Types\PropertyType;
use App\Models\Rate\RateYearType;
use Auth;
use App\Models\Loan\Loan;
use App\Models\UserSubscription;
use App\Http\Resources\Loan\LoanResource;
use App\Models\Loan\LoanOption;



class CommonController extends ParentController
{

    public function counties(Request $request)
    {
      $validator = Validator::make($request->all(), []);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
              $counties = County::select("id","name")->get();
              return response()->json([ $this->DATA  => $counties, $this->SUCCESS => true, $this->MSG  => ""], 200);
          }
          
    }

    public function types(Request $request)
    {
      $validator = Validator::make($request->all(), []);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
               $data = [ "property_types" => PropertyType::select("id","name")->get(),
                         "loan_types" => RateYearType::select("id","type as name")->get(),
                         "loan_use_types" => LoanUseType::select("id","name")->get()];
              return response()->json([ $this->DATA  => $data, $this->SUCCESS => true, $this->MSG  => ""], 200);
          }  
    }

    public function loanHistory(Request $request)
    {
      $validator = Validator::make($request->all(), []);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
              $user = Auth::user(); 
              $loans = Loan::where("user_id",$user->id)
             // ->where("loan_option_id", LoanOption::LowRate)
              ->orderBy("created_at","desc")
              ->with("optimal", "lender")
              // ->with(array('optimal' => function($query) {
              //          $query->select("monthly_payment as required_monthly_payment","rate","total_amount_to_pay","down_payment","credit_score","loan_amount","zip_code","created_at");
              //  }))

              ->take(20)->get();
              $loans = LoanResource::collection($loans);
              return response()->json([ $this->DATA  => $loans, $this->SUCCESS => true, $this->MSG  => ""], 200);
          }  
    }

    public function subscriptions(Request $request)
    {         $user = Auth::user();
              $subscriptions = UserSubscription::select("id","subscription")->get();
              return response()->json([ $this->DATA  => $subscriptions, $this->SUCCESS => true, $this->MSG  => ""], 200); 
    }
}




