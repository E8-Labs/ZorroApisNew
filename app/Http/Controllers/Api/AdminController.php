<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;
use App\Models\UserProfile;
use App\Models\UserSubscription;
use App\Models\Loan\Loan;
use App\Models\Loan\LoanCategory;
use App\Models\Lender;
use App\Http\Resources\Loan\LoanLiteResource;//
use App\Http\Resources\Loan\LoanResource;
use App\Http\Resources\LenderFullResource;

use App\Http\Resources\User\ProfileFreeOrPremiumResource;


class AdminController extends ParentController
{
	const NON_ADMIN = "You need to be admin to access this!!!";
	const TOTAL_REVENEUE = "total_revenue";

	const TOTAL_USERS_COUNT = "total_users_count";
	const FREE_USERS_COUNT = "free_users_count";
	const PREMIUM_USERS_COUNT = "premium_users_count";

	const FREE_NEW = "free_new";
	const FREE_EXISTING = "free_existing";
	const PREMIUM_NEW = "premium_new";
	const PREMIUM_EXISTING = "premium_existing";
	const LOAN_TOTAL = "loan_total";


	public function deleteUser(Request $request)
    {
    	   $validator = Validator::make($request->all(), ['user_id'=>'required']);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
	          	$user =  Auth::user();
	            if( Auth::user()->isAdmin()){

	           $userId =  $request['user_id'];
	           $user = User::where("id",$userId )->first();
	           if( $user == null) {
	            	return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => "User not found"], 200);
	            }
	            $user->delete();
		           return response()->json([$this->DATA => null, $this->SUCCESS => true, $this->MSG => $this->DELETED_SUCCESSFULLY], 200);
		        }else{
		        	 return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => self::NON_ADMIN], 200);
		        }
	       }
    }

    public function allLoansList(Request $request)
    {
    	   $validator = Validator::make($request->all(), []);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
	          	$user =  Auth::user();
	            if( Auth::user()->isAdmin()){

	           $term =  $request['term']; if($term == null) { $term = "" ;  }
	           //$type =  $request['type'];
	           $off_set = $request["off_set"]; if( $off_set == null ){ $off_set  = 0;  }
	           $pageSize = 30;
	           //$userId =  $request['user_id'];
	           //$user = User::where("id",$userId )->first();
	           //if($type != 1 && $type != 2 ){
	           //	return response()->json([$this->DATA => null , $this->SUCCESS => true, $this->MSG => "Invalid type"], 200);
	           //}
	           //if( $user == null) {
	           // 	return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => "User not found"], 200);
	           // }
                $loans = Loan::whereNull('loan_parent_id')->where(function ($q) {
    								$q->where('categories_id', LoanCategory::Free_New)
    								->orWhere('categories_id', LoanCategory::Premium_New)
    								->orWhere('categories_id', LoanCategory::Free_Existing)
    								->orWhere('categories_id', LoanCategory::Premium_Existing);
					})
	           	->take($pageSize)->skip($off_set )->get();

	   //        if( $type == 1 ){ // new 

	   //        	$loans = Loan::where("user_id",$userId )->where(function ($q) {
    // 								$q->where('categories_id', LoanCategory::Free_New)
    // 								->orWhere('categories_id', LoanCategory::Premium_New);
				// 	})
	   //        	->take($pageSize)->skip($off_set )->get();
	   //        }else{
	   //        	$loans = Loan::where("user_id",$userId )->where(function ($q) {
    // 								$q->where('categories_id', LoanCategory::Free_Existing)
    // 								->orWhere('categories_id', LoanCategory::Free_Existing);
				// 	})
	   //        	->take($pageSize)->skip($off_set )->get(); 	
	   //        }
		           return response()->json([$this->DATA => LoanLiteResource::collection($loans) , $this->SUCCESS => true, $this->MSG => ""], 200);
		        }else{
		        	 return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => self::NON_ADMIN], 200);
		        }
	       }
    }
    
    public function getLoanDetail(Request $request){
        $validator = Validator::make($request->all(), ['id'=>'required']);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
	          	$user =  Auth::user();
	            if( Auth::user()->isAdmin()){

	           
	           $id =  $request['id'];
	           
	           


	           $loan = Loan::where("id",$id )->first();
		           return response()->json([$this->DATA => new LoanResource($loan) , $this->SUCCESS => true, $this->MSG => ""], 200);
		        }else{
		        	 return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => self::NON_ADMIN], 200);
		        }
	       }
    }

    public function userLoans(Request $request)
    {
    	   $validator = Validator::make($request->all(), ['type'=>'required', 'user_id'=>'required']);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
	          	$user =  Auth::user();
	            if( Auth::user()->isAdmin()){

	           $term =  $request['term']; if($term == null) { $term = "" ;  }
	           $type =  $request['type'];
	           $off_set = $request["off_set"]; if( $off_set == null ){ $off_set  = 0;  }
	           $pageSize = 20;
	           $userId =  $request['user_id'];
	           $user = User::where("id",$userId )->first();
	           if($type != 1 && $type != 2 ){
	           	return response()->json([$this->DATA => null , $this->SUCCESS => true, $this->MSG => "Invalid type"], 200);
	           }
	           if( $user == null) {
	            	return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => "User not found"], 200);
	            }


	           if( $type == 1 ){ // new 

	           	$loans = Loan::where("user_id",$userId )->where(function ($q) {
    								$q->where('categories_id', LoanCategory::Free_New)
    								->orWhere('categories_id', LoanCategory::Premium_New);
					})
	           	->take($pageSize)->skip($off_set )->get();
	           }else{
	           	$loans = Loan::where("user_id",$userId )->where(function ($q) {
    								$q->where('categories_id', LoanCategory::Free_Existing)
    								->orWhere('categories_id', LoanCategory::Free_Existing);
					})
	           	->take($pageSize)->skip($off_set )->get(); 	
	           }
		           return response()->json([$this->DATA => LoanLiteResource::collection($loans) , $this->SUCCESS => true, $this->MSG => ""], 200);
		        }else{
		        	 return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => self::NON_ADMIN], 200);
		        }
	       }
    }


    public function userProfile(Request $request)
    {
    	  $validator = Validator::make($request->all(), ['user_id'=>'required']);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
	          	$user =  Auth::user();
	            if( Auth::user()->isAdmin()){

	           $userId =  $request['user_id'];

	            $user = User::where("id",$userId )->with('profile')->first();

	            if( $user == null) {
	            	return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => "User not found"], 200);
	            }
	            $freeNewCount = Loan::where("user_id",$userId )->where('categories_id', LoanCategory::Free_New )->count();
	            $PremiumNewCount = Loan::where("user_id",$userId )->where('categories_id', LoanCategory::Premium_New )->count();

	            $freeExistingCount = Loan::where("user_id",$userId )->where('categories_id', LoanCategory::Free_Existing )->count();
	            $PremiumExistingCount = Loan::where("user_id",$userId )->where('categories_id', LoanCategory::Premium_Existing )->count();

	            $data = [ 
	           			"profile" => new ProfileFreeOrPremiumResource( $user),
	           			"new_loans" => $freeNewCount + $PremiumNewCount,
	           			"existing_loans" => $freeExistingCount + $PremiumExistingCount

	           		];

	           
		           return response()->json([$this->DATA => $data, $this->SUCCESS => true, $this->MSG => ""], 200);
		        }else{
		        	 return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => self::NON_ADMIN], 200);
		        }
	       }
    }

	public function users(Request $request)
    {
    	   $validator = Validator::make($request->all(), ['type'=>'required']);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
	          	$user =  Auth::user();
	            if( Auth::user()->isAdmin()){

	           $term =  $request['term']; if($term == null) { $term = "" ;  }
	           $type =  $request['type'];
	           $off_set = $request["off_set"]; if( $off_set == null ){ $off_set  = 0;  }
	           $pageSize = 20;

	           if($type != 1 && $type != 2 ){
	           	return response()->json([$this->DATA => null , $this->SUCCESS => true, $this->MSG => "Invalid type"], 200);
	           }

	           if($type == 1 ){ // free 
	           	  if( $term == ""  ){
	                $data = User::whereDoesntHave('profile')->with('profile')->orderBy('created_at','DESC')->take($pageSize)->skip($off_set)->get();
	              }else{
	           		$data = User::whereDoesntHave('profile')->where('name', 'LIKE','%' . $term . '%')->with('profile')->orderBy('created_at','DESC')->take($pageSize)->skip($off_set)->get();
	              }
	           }else{
	           	  if( $term == ""  ){
	           		$data = User::has('profile')->with('profile')->orderBy('created_at','DESC')->take($pageSize)->skip($off_set)->get();
	           }else{
	           		$data = User::whereHas('profile', function($q) use($term) {
   						 $q->where('name', 'LIKE','%' . $term . '%');
					})->with('profile')
					->orderBy('created_at','DESC')->take($pageSize)->skip($off_set)
	           		->get();
	           }

	           }
		           return response()->json([$this->DATA => ProfileFreeOrPremiumResource::collection($data) , $this->SUCCESS => true, $this->MSG => ""], 200);
		        }else{
		        	 return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => self::NON_ADMIN], 200);
		        }
	       }
    }

    public function lendersList(Request $request){
    	$validator = Validator::make($request->all(), []);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
          		$user =  Auth::user();
	            if( !Auth::user()->isAdmin()){
	            	return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => self::NON_ADMIN], 200);
	            }
	            $lenders = Lender::all();
	            return response()->json([$this->DATA => LenderFullResource::collection($lenders) , $this->SUCCESS => true, $this->MSG => ""], 200);
          }
    }
    
    public function dashboard(Request $request)
    {
    	   $validator = Validator::make($request->all(), []);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
	          	$user =  Auth::user();
	            if( Auth::user()->isAdmin()){

	            $premium_users_count = UserProfile::count();
	            $total_users_count = User::count();	

	            $freeNewCount =Loan::where('categories_id', LoanCategory::Free_New )->count();
	            $freeExistingCount =Loan::where('categories_id', LoanCategory::Free_Existing )->count();
	            $premiumNewCount =Loan::where('categories_id', LoanCategory::Premium_New )->count();
	            $premiumExistingCount =Loan::where('categories_id', LoanCategory::Premium_Existing )->count();

	            $lenders = Lender::all();
	            $lenderCounts =array( );
	            $lenderTotalCount = 0;

	            foreach ($lenders as $lender) {
	            	$temp =array( );
	            	$temp = ['name' => $lender->name , 'count' =>  Loan::where('lender_id', $lender->id )->count() ];
	            	$lenderCounts[] =  $temp;
	            	$lenderTotalCount += $temp['count'];
	            }

	            $total_revenue = Loan::sum('loan_amount');

	           $loanData = [ 
	           			self::FREE_NEW =>  $freeNewCount,
	           			self::FREE_EXISTING =>  $freeExistingCount,
	           			self::PREMIUM_NEW =>  $premiumNewCount,
	           			self::PREMIUM_EXISTING =>  $premiumExistingCount,
	           			self::LOAN_TOTAL => $freeNewCount + $freeExistingCount + $premiumNewCount + $premiumExistingCount,

	           		];
	          $userData = [ 
	           			self::TOTAL_USERS_COUNT => $total_users_count,
	           			self::PREMIUM_USERS_COUNT => $premium_users_count,
	           			self::FREE_USERS_COUNT => $total_users_count - $premium_users_count,
	           		];
	           $lenderData = [ 
	           			'lender_total_count' => $lenderTotalCount,
	           			"lenders" => $lenderCounts
	           			
	           		];
	           $data = [ 
	           			self::TOTAL_REVENEUE => $total_revenue,
	           			'user' => $userData,
	           			'loan' => $loanData,
	           			'lender' => $lenderData,

	           		];
		           return response()->json([$this->DATA => $data , $this->SUCCESS => true, $this->MSG => ""], 200);
		        }else{
		        	 return response()->json([$this->DATA => null , $this->SUCCESS => false, $this->MSG => self::NON_ADMIN], 200);
		        }
	          }
    }
}


