<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Types\PropertyType as P_Type;
use App\Http\Traits\CalculationTrait;
use App\User;
use App\Models\Lender;
use App\Models\Loan\Loan;
use App\Models\Loan\LoanCategory;
use Auth;
use App\Models\Loan\LoanOption;
use App\Models\Loan\LoanSection;
use App\Models\Types\LoanUseType;
use App\Models\Types\PropertyType;

class CalculationController extends ParentController
{
    use CalculationTrait;

    var $LENDER = "lender";
    var $lender_ids = [1,2,3];
    var $NEW_LOAN = "new_loan";
    var $LOW_COST_LOAN = "low_cost";
    var $LOW_RATE_LOAN = "low_rate";
    var $EXISTING_LOAN = "existing_loan";
    var $OPTIMAL_LOAN = "optimal_loan";

    var $TEMP_OPTIMAL_LOAN_VALUE = 2.75; //2.875;
    var $TEMP_LOST_COST_LOAN_VALUE = 2.875;// 2.99;

    var $TEMP_LOW_RATE_LOAN_VALUE = -1 ; // -1; // -1 2.625 for actual values 

    // var $RULE_LOAN_AMOUNT = 'required|digits_between:1,12';
    var $RULE_LOAN_AMOUNT = 'required';

    public function freeCalculation(Request $request)
    {
      $validator = Validator::make($request->all(), ['loan_amount' => $this->RULE_LOAN_AMOUNT,'device_id' => 'required']); 
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
            $loanAmount =  $request['loan_amount'];
            // $downpayment = $request["down_payment"];
            // $loanAmount = $loanAmout - $downpayment;
            $device_id = $request['device_id'];
            $zipCode =  $request['zip_code']; if($zipCode == null){$zipCode = 0;}
            $name = $request['name'];if($name == null){$name = "";}
            $user = User::getUserWithDeviceId($device_id, $name);
            
            $creditScore = 720;
            $lender_id = 1;
            $ltv = 80;
            $numberOfPayments = 360;
            $years = 30;
 
            $result = NULL;//$this->getLoan($lender_id,$ltv,$creditScore, $loanAmount , $numberOfPayments,  $this->TEMP_LOW_RATE_LOAN_VALUE);
            $lenders = Lender::whereIn('id', $this->lender_ids)->get();
            $len = Lender::where('id', 1)->first();
            
            foreach($lenders as $lender){
                $lender_id = $lender->id;
                $sheet_type = $this->getSectionFromLoan($loanAmount, PropertyType::Single_Family, $lender_id);
                $section= $this->multipleSubsectionsSelection($sheet_type , PropertyType::Single_Family,LoanUseType::Primary, $ltv, $years, $loanAmount, LoanCategory::Free_New, $lender_id);
                
                $det = "";
                // if($section != NULL){
                //     $det = $det . "\nLender Name => " . $lender->name . "\n Sheet : " . $sheet_type . " \n LTV : " . $ltv;
                //     $count = count($section);
                //     for($i = 0; $i < $count; $i++){
                //         $sec = $section[$i];
                //         $det = $det . $sec->detail() . "\n";
                //     }
                //     return  $det;
                // }
                
                
                // return $section;
                $lowestResult = $this->getLoan($lender_id,$ltv,$creditScore, $loanAmount , $numberOfPayments, $this->TEMP_LOW_RATE_LOAN_VALUE, $section, $years, LoanCategory::Free_New, $sheet_type);
                // return $lowestResult;
                if($result == NULL){
                    $result = $lowestResult;
                    $len = $lender;
                }
                else if($lowestResult->data["rate"] < $result->data["rate"]){
                    $result = $lowestResult;
                    $len = $lender;
                }
            }
            
            $data = [$this->NEW_LOAN => $result->data, 
                     $this->LENDER => $this->getLender($lender_id)];

                $down_payment=0; 
                $proprety_type_id=P_Type::Single_Family;
                $loan_type_id=null;
                $use_type_id=null;
                $loan_option_id = LoanOption::LowRate; ; $loan_parent_id = null; $propertyValue = 0;
                Loan::saveLoan($user->id,LoanCategory::Free_New, $lender_id,$result->requiredMonthlyPayment,(float)$result->data["rate"], $result->totalAmountToPay, $down_payment, $creditScore, $loanAmount, $zipCode,$proprety_type_id,$loan_type_id, $use_type_id,
                $loan_option_id, $loan_parent_id, $propertyValue ); 

                return response()->json([$this->DATA => $data,$this->SUCCESS => true, $this->MSG => ""], 200);
          }
    }

    public function freeExistingCalculation(Request $request)
    {
      $validator = Validator::make($request->all(), ['loan_amount' => $this->RULE_LOAN_AMOUNT,'existing_rate' => 'required','monthly_mortgage' => 'required','device_id' => 'required']);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
            $loanAmount =  $request['loan_amount'];
            $loan_start_date =  $request['loan_start_date'];
            $existingRate =  $request['existing_rate'];
            $monthlyMortgage =  $request['monthly_mortgage'];
            $device_id = $request['device_id'];
            $zipCode =  $request['zip_code']; if($zipCode == null){$zipCode = 0;}
            $name = $request['name'];if($name == null){$name = "";}
            $user = User::getUserWithDeviceId($device_id, $name);

            $creditScore = 720;
            $lender_id = 1;
            $ltv = 80;
            $years = 30;
            $numberOfPayments = 360;
            $lenders = Lender::whereIn('id', $this->lender_ids)->get();
            
            $lowestResult = NULL;//$this->getLoan($lender_id,$ltv,$creditScore, $loanAmount , $numberOfPayments, $this->TEMP_LOW_RATE_LOAN_VALUE);
            // $d = $lowestResult->data["rate"];
            // return response()->json([$this->DATA => $d,$this->SUCCESS => true, $this->MSG => ""], 200);
            $len = Lender::where('id', 1)->first();
            foreach($lenders as $lender){
                $lender_id = $lender->id;
                $sheet_type = $this->getSectionFromLoan($loanAmount, PropertyType::Single_Family, $lender_id);
                $section= $this->multipleSubsectionsSelection($sheet_type , PropertyType::Single_Family,LoanUseType::Primary, $ltv, $years, $loanAmount, LoanCategory::Free_Existing, $lender_id);
                
                // $det = "";
                // if($section != NULL){
                //     $det = $det . "\nLender Name => " . $lender->name . "\n Sheet : " . $sheet_type . " \n LTV : " . $ltv;
                //     $count = count($section);
                //     for($i = 0; $i < $count; $i++){
                //         $sec = $section[$i];
                //         $det = $det . $sec->detail() . "\n";
                //     }
                //     return  $det;
                // }
                
                
                // return $section;
                $result = $this->getLoan($lender_id,$ltv,$creditScore, $loanAmount , $numberOfPayments, $this->TEMP_LOW_RATE_LOAN_VALUE, $section, $years, LoanCategory::Free_Existing, $sheet_type);
                // return $lowestResult;
                if($lowestResult == NULL){
                    $lowestResult = $result;
                    $len = $lender;
                }
                else if($result->data["rate"] < $lowestResult->data["rate"]){
                    $lowestResult = $result;
                    $len = $lender;
                }
            }
            
            $existingResult = $this->getExistingLoan($lender_id,$ltv, 0,$creditScore, $loanAmount , $numberOfPayments,$existingRate, $monthlyMortgage, $loan_start_date );

            $down_payment=0; $proprety_type_id=P_Type::Single_Family;;$loan_type_id=null;$use_type_id=null;
            $loan_option_id = LoanOption::LowRate; ; $loan_parent_id = null; $propertyValue = 0;
            Loan::saveLoan($user->id,LoanCategory::Free_Existing, $lender_id,$lowestResult->requiredMonthlyPayment,(float)$lowestResult->data["rate"], $lowestResult->totalAmountToPay, $down_payment, $creditScore,$loanAmount, $zipCode,$proprety_type_id,$loan_type_id, $use_type_id,
            $loan_option_id, $loan_parent_id, $propertyValue  ); 

            $data = [ $this->EXISTING_LOAN => $existingResult->data,
                      $this->NEW_LOAN => $lowestResult->data, 
                      $this->LENDER => $len];

            return response()->json([$this->DATA => $data,$this->SUCCESS => true, $this->MSG => ""], 200);
          }
    }

    public function premiumCalculation(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'loan_amount'=>$this->RULE_LOAN_AMOUNT,'credit_score'=>'required','down_payment'=>'required',
          'loan_type_id' => 'required','property_type_id' => 'required' ,'use_type_id' => 'required' ,'zip_code' => 'required']);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
            $loanAmount =  $request['loan_amount'];
            // $existingRate =  $request['existing_rate']; if($existingRate == null ) { $existingRate = -1; }
            $creditScore = $request['credit_score'];
            // return $creditScore;
            $downPayment = $request['down_payment'];
            $loanAmount = $loanAmount - $downPayment;
            $loanTypeId =  $request['loan_type_id'];
            $propretyTypeId =  $request['property_type_id'];
            if($propretyTypeId == P_Type::Townhomes){ // TownHomes is considered as Single Family
                $propretyTypeId = P_Type::Single_Family;
            }
            $useTypeId =  $request['use_type_id'];
            $zipCode =  $request['zip_code'];
            // return $loanAmount;
            $propertyValue =  $request['property_value']; 
            if($propertyValue == null ) {
              $propertyValue =  $request['loan_amount']; 
              if($propertyValue == null ) {
                  $propertyValue = 0; 
              }
                
            }
            // $lender_id = 1;
            $numberOfPayments = 360;
            $user = Auth::user();
            $ltv = 0;

            $msg = $this->validatePremiumLoanRequest($creditScore,$downPayment, $loanAmount);
            if($msg != "true"){
             return response()->json([$this->MSG  => $msg ,$this->SUCCESS => false], 200);
            }

            // $tempLoanAmount = $loanAmount - $downPayment;
            // $ltv = ($tempLoanAmount/$loanAmount) * 100;
            if($propertyValue > 0)
            {
              $ltv = ($loanAmount/$propertyValue) * 100;

            }
            //  return $loanAmount . " / " . $propertyValue;
            $creditScore = $this->getCreditScoreFromValue($creditScore);
            // return $creditScore;
            $valid = $this->isValidLoan($ltv, $propretyTypeId, $useTypeId);
            // return $valid;
            if($valid == false){
                // return "Invalid loan " . $valid;
                return response()->json([$this->MSG  => "LTV > 75 not allowed for 2-4 units for secondary and investment types." ,$this->SUCCESS => false, $this->DATA => NULL], 200);
            }
            
            
            if($ltv <= 80)
            {
              $pmi = 0 ;
              $monthly_private_mortgage_insurance = 0;
            }
            else
            {
              if ($ltv > 80 && $ltv <= 90){
                $pmi = 0.0022 * $loanAmount;
              }
              else if ( $ltv > 90 && $ltv <= 95){
                $pmi = 0.004 * $loanAmount;
              }
              else{
                $pmi = 0.006 * $loanAmount;
                
              }
              //$pmi = 0.22 * $loanAmount;
              $monthly_private_mortgage_insurance = $pmi /12;
            }

            //////////////////////////////////////////////////////////////////
            
            //LoanSection::ConformingFixed
            $years = $this->getYearTermValue($loanTypeId);


            $lenders = Lender::whereIn('id', $this->lender_ids)->get();//where('id', 2)->
            // return $lenders;
            
            $lowest = false; // if found the lowest then set the value to true
            $lender_Response = new Lender;
            $sheet_type_Response = 0;
            $result = new Loan;
            $resultOptimal = new Loan;
            $resultLowCost = new Loan;
            
            $det = "";
            $lenderAddOns = array();
            $allLenderRates = array();
            $lowest = false; // if found the lowest then set the value to true
            $errorArray = array();
            
            foreach($lenders as $lender){
                $lender_id = $lender->id;
                $sheet_type = $this->getSectionFromLoan($loanAmount, $propretyTypeId, $lender_id);
                // $sheet_type = LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE;
                $section= $this->multipleSubsectionsSelection($sheet_type , $propretyTypeId,$useTypeId, $ltv, $years, $loanAmount, LoanCategory::Premium_New, $lender_id);
                // return $section;
                // return $sheet_type;
                $isvalidJumbo = $this->isValidJumboLoan($sheet_type, $years);
                if($isvalidJumbo == false){
                // return "Invalid loan " . $valid;
                    return response()->json([$this->MSG  => "Jumbo loans are only allowed for 30 year terms." ,$this->SUCCESS => false, $this->DATA => NULL], 200);
                }
                $errorsForLender = array();
                if($section != NULL){
                    $det = $det . "\nLender Name => " . $lender->name . "\n Sheet : " . $sheet_type . " \n LTV : " . $ltv . "\n";
                    $count = count($section);
                    for($i = 0; $i < $count; $i++){
                        $sec = $section[$i];
                        $det = $det . $sec->detail() . " LTV : " . $ltv . " cs " . $creditScore . "\n";
                    }
                // return  $det;
            //     $cs = $this->getCreditScoreWithSection( $creditScore , $ltv , $lender_id , $section, $loanAmount, LoanCategory::Premium_Existing, $years);
            //   return response()->json([$this->DATA => $cs, $this->SUCCESS => true,$this->MSG => $det . $sheet_type], 200);

                // $section = LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE;
                $result_Response = $this->getLoanWithSection($section,$lender_id,$ltv,$monthly_private_mortgage_insurance,$creditScore, $loanAmount + $downPayment , $numberOfPayments, $this->TEMP_LOW_RATE_LOAN_VALUE,"",$downPayment, "", $loanTypeId, LoanCategory::Premium_New, $years);
                
                    if (is_array($result_Response) && isset($result_Response["message"])){ // if it is an error message then array
                            $errorsForLender[] = $result_Response["message"];
                    }
                    else{
                        // return $result_Response;
            //       return response()->json([$this->DATA => $result_Response,$this->SUCCESS => true,$this->MSG => ""], 200);
                    
                    $resultOptimal_Response = $this->getLoanWithSection($section,$lender_id,$ltv,$monthly_private_mortgage_insurance,$creditScore, $loanAmount + $downPayment , $numberOfPayments,     $result_Response->low_rate_rate,"",$downPayment, $result_Response->low_rate_costOrCredit, $loanTypeId, LoanCategory::Premium_New, $years);
    
            // r    eturn response()->json([$this->DATA => $resultOptimal_Response,$this->SUCCESS => true,$this->MSG => ""], 200);
    
                     $resultLowCost_Response = $this->getLoanWithSection($section,$lender_id,$ltv,$monthly_private_mortgage_insurance,$creditScore, $loanAmount + $downPayment , $numberOfPayments,    $result_Response->low_cost_rate,"",$downPayment,$result_Response->low_cost_costOrCredit, $loanTypeId, LoanCategory::Premium_New, $years);
    
                    if($sheet_type == LoanSection::JmacManhattanJumbo && $lender->id == 1){
                    //   echo "Calculate Purchase";
                          $section= $this->multipleSubsectionsSelection(LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE , $propretyTypeId,$useTypeId, $ltv, $years,       $loanAmount);
        $det = $det . "\nLender Name => " . $lender->name . "\n Sheet : " . LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE . " \n LTV : " . $ltv . "\n";
                    $count = count($section);
                    for($i = 0; $i < $count; $i++){
                        $sec = $section[$i];
                        $det = $det . $sec->detail() . " LTV : " . $ltv . " cs " . $creditScore . "\n";
                    }
                // return  $det;
                            // $this->getLoanWithSection($section,$lender_id,$ltv,$monthly_private_mortgage_insurance,$creditScore, $loanAmount + $downPayment , $numberOfPayments, $this->TEMP_LOW_RATE_LOAN_VALUE,"",$downPayment, "", $loanTypeId, LoanCategory::Premium_New, $years);
                          $result1 = $this->getLoanWithSection($section,$lender_id,$ltv      ,$monthly_private_mortgage_insurance,$creditScore, $loanAmount + $downPayment , $numberOfPayments,         $this->TEMP_LOW_RATE_LOAN_VALUE,'',$downPayment, "", $loanTypeId, LoanCategory::Premium_New, $years);
                          // if (is_array($result)){ // if it is an error message then array
                          //     if(isset($result["status"])){
                          //       return response()->json([$this->DATA => $result,$this->SUCCESS => false,$this       ->MSG => ""], 200);
                          //     }
                          // }
                          $resultOptimal1 = $this->getLoanWithSection($section,$lender_id,$ltv      ,$monthly_private_mortgage_insurance,$creditScore, $loanAmount + $downPayment , $numberOfPayments,         $result1->low_rate_rate,'',$downPayment, $result1      ->low_rate_costOrCredit, $loanTypeId, LoanCategory::Premium_New, $years);
                          $resultLowCost1 = $this->getLoanWithSection($section,$lender_id,$ltv      ,$monthly_private_mortgage_insurance,$creditScore, $loanAmount + $downPayment , $numberOfPayments,         $result1->low_cost_rate,'',$downPayment,$result1      ->low_cost_costOrCredit, $loanTypeId, LoanCategory::Premium_New, $years);
        // echo "Purchase " . $resultOptimal1->data["rate"] . " | " . "Manhattan " .  $resultOptimal_Response->data["rate"];
                          if($resultOptimal1->data["rate"] < $resultOptimal_Response->data["rate"]){ // if refinance rate       is lower 
                        //   echo "Purchase is lower";
                              $sheet_type = LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE;
                              $result_Response = $result1;
                              $resultOptimal_Response = $resultOptimal1;
                              $resultLowCost_Response = $resultLowCost1;
                          }
                    }
    
                     if($lowest == false){
                          // $det = $det . " \nNew Lender \n";
                            $lowest = true;
                            $resultOptimal = $resultOptimal_Response;
                            $resultLowCost = $resultLowCost_Response;
                            $result = $result_Response;
                            $sheet_type_Response = $sheet_type;
                            $lender_Response = $lender;
                        }
                        else if($resultOptimal_Response->data["rate"] < $resultOptimal->data["rate"]){ 
                          $det = $det . " \n" . $lender_Response->name . " > " . $lender->name . "\n"; 
                          $lowest = true;
                            $resultOptimal = $resultOptimal_Response;
                            $resultLowCost = $resultLowCost_Response;
                            $result = $result_Response;
                            $sheet_type_Response = $sheet_type;
                            $lender_Response = $lender;
                        }
                    }
                    $errorArray[$lender->name] = $errorsForLender;
                }
                else{
                    $errorsForLender[] = "Loan is invalid";
                }

              }


            

            // return $ltv;
            // return  $section->detail(); //"Section : " . $section->section . "  Sub Section : " . $section->subSection . "  Row : " . $section->row ;

            ////////////////////////////////////////////////////////////////
              // $result == optimal 
              $loan_option_id = LoanOption::LowRate; $loan_parent_id = null;
             $loan = Loan::saveLoan($user->id,LoanCategory::Premium_New, $lender_id,$resultOptimal->requiredMonthlyPayment,(float)$resultOptimal->data["rate"], $resultOptimal->totalAmountToPay, $downPayment, $creditScore,$loanAmount, $zipCode,$propretyTypeId,$loanTypeId, $useTypeId,
              $loan_option_id, $loan_parent_id, $propertyValue );
              

              $loan_option_id = LoanOption::Optimal; $loan_parent_id = $loan->id;
              Loan::saveLoan($user->id,LoanCategory::Premium_New, $lender_id,$result->requiredMonthlyPayment,(float)$result->data["rate"], $result->totalAmountToPay, $downPayment, $creditScore,$loanAmount, $zipCode,$propretyTypeId,$loanTypeId, $useTypeId,
                    $loan_option_id,$loan_parent_id,$propertyValue  ); 

              
              
              $loan_option_id = LoanOption::LowCost; $loan_parent_id = $loan->id;
             Loan::saveLoan($user->id,LoanCategory::Premium_New, $lender_id,$resultLowCost->requiredMonthlyPayment,(float)$resultLowCost->data["rate"], $resultLowCost->totalAmountToPay, $downPayment, $creditScore,$loanAmount, $zipCode,$propretyTypeId,$loanTypeId, $useTypeId,
              $loan_option_id, $loan_parent_id, $propertyValue );
              
               $data = [
                        "all_errors" => $errorArray,
                        $this->LOW_RATE_LOAN => $resultOptimal->data,
                        $this->OPTIMAL_LOAN => $result->data, 
                        $this->LOW_COST_LOAN => $resultLowCost->data,
                        $this->LENDER => $lender_Response,
                     //  $this->NEW_LOAN => $result->data, // WILL DELETE IN FUTURE

                     ];
             
            return response()->json([$this->DATA => $data,$this->SUCCESS => true,$this->MSG => ""], 200);
          }
    }


   public function premiumExistingCalculation(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'loan_amount'=>$this->RULE_LOAN_AMOUNT,'credit_score'=>'required' ,'monthly_mortgage' => 'required'
         ,'loan_type_id' => 'required','property_type_id' => 'required' ,'use_type_id' => 'required' ,'zip_code' => 'required'  ]);
      // , 'down_payment'=>'required'
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
            $loanAmount =  $request['loan_amount'];
            $existingRate =  $request['existing_rate']; if($existingRate == null ) { $existingRate = -1; }
            $creditScore = $request['credit_score'];
            $downPayment = $request['down_payment'];
            $loan_start_date =  $request['loan_start_date'];
            $monthlyMortgage =  $request['monthly_mortgage'];

            $loanTypeId =  $request['loan_type_id'];
            $propretyTypeId =  $request['property_type_id'];
            if($propretyTypeId == P_Type::Townhomes){
                $propretyTypeId = P_Type::Single_Family;
            }
            $useTypeId =  $request['use_type_id'];
            $zipCode =  $request['zip_code'];
            $original_loan_amount = (double)$request['original_loan_amount'];
            $propertyValue =  $request['property_value']; if($propertyValue == null ) { $propertyValue = 0; }

            $user = Auth::user();

            

            $lender_id = 1;
            $numberOfPayments = 360;
            $ltv = 0;

            $msg = $this->validatePremiumLoanRequest($creditScore,$downPayment, $loanAmount);
            if($msg != "true"){
             return response()->json([$this->MSG  => $msg ,$this->SUCCESS => false], 200);
            }

            // $tempLoanAmount = $loanAmount - $downPayment;
            // $ltv = ($tempLoanAmount/$loanAmount) * 100;
            if($propertyValue > 0)
            {
              $ltv = ($loanAmount/$propertyValue) * 100;
            }
            $valid = $this->isValidLoan($ltv, $propretyTypeId, $useTypeId);
            if($valid == false){
                // return "Invalid loan " . $valid;
                return response()->json([$this->MSG  => "LTV > 75 not allowed for 2-4 units for secondary and investment types." ,$this->SUCCESS => false, $this->DATA => NULL], 200);
            }
            
            // if($ltv > 80 && $useTypeId == LoanUseType::Investment){
            //   return response()->json([$this->MSG  => "LTV cannot exceed 80%" ,$this->SUCCESS => false, $this->DATA => NULL], 200);
            // }
            $creditScore = $this->getCreditScoreFromValue($creditScore);
            if($ltv <= 80)
            {
              $monthly_private_mortgage_insurance = 0;
            }
            else
            {
              if ($ltv > 80 && $ltv <= 90){
                $pmi = 0.0022 * $loanAmount;
              }
              else if ( $ltv > 90 && $ltv <= 95){
                $pmi = 0.004 * $loanAmount;
              }
              else{
                $pmi = 0.006 * $loanAmount;
                
              }
              //$pmi = 0.22 * $loanAmount;
              $monthly_private_mortgage_insurance = $pmi /12;
            }
            
            // $lender = Lender::where('id', $lender_id)->first();
            
            
           //////////////////////////////////////////////////////////////////
            
            //LoanSection::ConformingFixed
            $years = $this->getYearTermValue($loanTypeId);
            
            $lenders = Lender::whereIn('id', $this->lender_ids)->get();//where('id', 2)->
            
            
            $lowest = false; // if found the lowest then set the value to true
            $section_response;
            $lender_Response = new Lender;
            $sheet_type_Response = 0;
            $result_Response = new Loan;
            $resultOptimal_Response = new Loan;
            $resultLowCost_Response = new Loan;
            
            $det = "";
            $lenderAddOns = array();
            $allLenderRates = array();
            
            $errorArray = array();
            foreach($lenders as $lender){
                $lender_id = $lender->id;
                $sheet_type = $this->getSectionFromLoan($loanAmount, $propretyTypeId, $lender_id);
                // return $sheet_type;
                $isvalidJumbo = $this->isValidJumboLoan($sheet_type, $years);
                if($isvalidJumbo == false){
                // return "Invalid loan " . $valid;
                    return response()->json([$this->MSG  => "Jumbo loans are only allowed for 30 year terms." ,$this->SUCCESS => false, $this->DATA => NULL], 200);
                }
                $section= $this->multipleSubsectionsSelection($sheet_type , $propretyTypeId,$useTypeId, $ltv, $years, $loanAmount, LoanCategory::Premium_Existing, $lender_id); // pass lender id 
                $errorsForLender = array();
                if($section == NULL){
                    $errorsForLender[] = "Loan is invalid";
                    // return response()->json([$this->MSG  => "". $lender_id. " Sheet ". $sheet_type . " Is NULL"  ,$this->SUCCESS => false, $this->DATA => $section], 200);// . " is valid jumbo " . $isvalidJumbo;
                }
                else{
                    $section_response = $section;
                    // return response()->json([$this->MSG  => "". $lender_id. " Sheet ". $sheet_type  ,$this->SUCCESS => false, $this->DATA => $section], 200);// . " is valid jumbo " . $isvalidJumbo;
                
                // $det = $det . "\nLender Name => " . $lender->name . "\n Sheet : " . $sheet_type . " \n LTV : " . $ltv . "  | " . count($section) . "\n";
                // if(is_array($section)){
                    // $count = count($section);
            
            
                    // for($i = 0; $i < $count; $i++){
                    //     $sec = $section[$i];
                    //     $det = $det . $sec->detail() . "\n";
                    // }
                // }
                // 
                // return  $det;
            //   $cs = $this->getCreditScoreWithSection( $creditScore , $ltv , $lender_id , $section, $loanAmount, LoanCategory::Premium_Existing, $years);
            //   return response()->json([$this->DATA => $cs, $this->SUCCESS => true,$this->MSG => $det . $sheet_type], 200);
            //   return "Downpayment is " . $downPayment;
                $result = $this->getLoanWithSection($section,$lender_id,$ltv,$monthly_private_mortgage_insurance,$creditScore, $loanAmount , $numberOfPayments, $this->TEMP_LOW_RATE_LOAN_VALUE,$loan_start_date,$downPayment, 0, $loanTypeId, LoanCategory::Premium_Existing, $years);
                $lenderAddOns[$lender->name] = $result;
                //   return response()->json([$this->DATA => $result,$this->SUCCESS => true,$this->MSG => $lender->name . " => " . $sheet_type], 200);
                    if (is_array($result) && isset($result["message"])){ // if it is an error message then array
                        $errorsForLender[] = $result["message"];
                     }
                     else{
                        //  $errorsForLender[] = "No error for now";
                         $resultOptimal = $this->getLoanWithSection($section,$lender_id,$ltv,$monthly_private_mortgage_insurance,$creditScore, $loanAmount , $numberOfPayments, $result->low_rate_rate,$loan_start_date,$downPayment, $result->low_rate_costOrCredit, $loanTypeId, LoanCategory::Premium_Existing, $years);
                
                        $resultLowCost = $this->getLoanWithSection($section,$lender_id,$ltv,$monthly_private_mortgage_insurance,$creditScore, $loanAmount , $numberOfPayments, $result->low_cost_rate,$loan_start_date,$downPayment,$result->low_cost_costOrCredit, $loanTypeId, LoanCategory::Premium_Existing, $years);
                
                
                      if($sheet_type == LoanSection::JmacManhattanJumbo && $lender->id == 1){
                      
                          $section= $this->multipleSubsectionsSelection(LoanSection      ::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE , $propretyTypeId,$useTypeId, $ltv, $years,       $loanAmount);
        
                          $result1 = $this->getLoanWithSection($section,$lender_id,$ltv      ,$monthly_private_mortgage_insurance,$creditScore, $loanAmount , $numberOfPayments,         $this->TEMP_LOW_RATE_LOAN_VALUE,$loan_start_date,$downPayment, 0, $loanTypeId, LoanCategory::Premium_Existing, $years);
                          // if (is_array($result)){ // if it is an error message then array
                          //     if(isset($result["status"])){
                          //       return response()->json([$this->DATA => $result,$this->SUCCESS => false,$this       ->MSG => ""], 200);
                          //     }
                          // }
                          $resultOptimal1 = $this->getLoanWithSection($section,$lender_id,$ltv      ,$monthly_private_mortgage_insurance,$creditScore, $loanAmount , $numberOfPayments,         $result1->low_rate_rate,$loan_start_date,$downPayment, $result1      ->low_rate_costOrCredit, $loanTypeId, LoanCategory::Premium_Existing, $years);
                          $resultLowCost1 = $this->getLoanWithSection($section,$lender_id,$ltv      ,$monthly_private_mortgage_insurance,$creditScore, $loanAmount , $numberOfPayments,         $result1->low_cost_rate,$loan_start_date,$downPayment,$result1      ->low_cost_costOrCredit, $loanTypeId, LoanCategory::Premium_Existing, $years);
        
                          if($resultOptimal1->data["rate"] < $resultOptimal->data["rate"]){ // if refinance rate       is lower 
                               $sheet_type = LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE;
                              $result = $result1;
                              $resultOptimal = $resultOptimal1;
                              $resultLowCost = $resultLowCost1;
                          }
                    }
                    $det = $det . "\n " . $lender->name . " => " . $resultOptimal->data["rate"];
                    
                    if($lowest == false){
                      $det = $det . " \nNew Lender \n";
                        $lowest = true;
                        $resultOptimal_Response = $resultOptimal;
                        $resultLowCost_Response = $resultLowCost;
                        $result_Response = $result;
                        $sheet_type_Response = $sheet_type;
                        $lender_Response = $lender;
                    }
                    else if($resultOptimal->data["rate"] < $resultOptimal_Response->data["rate"]){ 
                      $det = $det . " \n" . $lender_Response->name . " > " . $lender->name . "\n"; 
                      $lowest = true;
                               $resultOptimal_Response = $resultOptimal;
                        $resultLowCost_Response = $resultLowCost;
                        $result_Response = $result;
                        $sheet_type_Response = $sheet_type;
                        $lender_Response = $lender;
                    }


                        $allLenderRates[$lender->name] = [
                'section' => $section_response,
                        "sheet" => $sheet_type_Response,
                      $this->OPTIMAL_LOAN =>  $result->data,
                      $this->LOW_RATE_LOAN =>  $resultOptimal->data,  
                      $this->LOW_COST_LOAN => $resultLowCost->data,
                      "LenderAddOns" => $lenderAddOns];
                     }
                

                
                }
                $errorArray[$lender->name] = $errorsForLender;
                
            }
            //foreach loop ends for lenders
              // return $det;
              
              
            
            // 
            $exittingResult = $this->getExistingLoan($lender_id,$ltv,$monthly_private_mortgage_insurance,$creditScore, $loanAmount , $numberOfPayments,$existingRate, $monthlyMortgage , $loan_start_date);
            $exittingResult->data["original_loan_amount"] = $original_loan_amount;
            //return  round($result->totalAmountToPay, 2);
            
            $loan_option_id = LoanOption::LowRate; $loan_parent_id = null;
             $loan = Loan::saveLoan($user->id,LoanCategory::Premium_Existing, $lender_id,$resultOptimal_Response->requiredMonthlyPayment,(float)$resultOptimal_Response->data["rate"], $resultOptimal_Response->totalAmountToPay, $downPayment, $creditScore,$loanAmount, $zipCode,$propretyTypeId,$loanTypeId, $useTypeId,
              $loan_option_id, $loan_parent_id, $propertyValue );

            $loan_option_id = LoanOption::Optimal; $loan_parent_id = $loan->id; 
            Loan::saveLoan($user->id,LoanCategory::Premium_Existing, $lender->id,$result_Response->requiredMonthlyPayment,(float)$result_Response->data["rate"], $result_Response->totalAmountToPay, $downPayment, $creditScore,$loanAmount, $zipCode,$propretyTypeId,$loanTypeId, $useTypeId,
             $loan_option_id,$loan_parent_id,$propertyValue );
             
             
             //Saving Existing Loan
             $loan_option_id = LoanOption::Existing; $loan_parent_id = $loan->id; 
            Loan::saveLoan($user->id,LoanCategory::Premium_Existing, $lender->id,$exittingResult->requiredMonthlyPayment,(float)$exittingResult->data["rate"], $exittingResult->totalAmountToPay, $downPayment, $creditScore,$loanAmount, $zipCode,$propretyTypeId,$loanTypeId, $useTypeId,
             $loan_option_id,$loan_parent_id,$propertyValue,  (float)$exittingResult->data["private_mortgage_insurance"], 
             (int)$exittingResult->data["number_of_payments_till_date"],
             (float)$exittingResult->data["interest_paid_till_date"],
             (float)$exittingResult->data["Interest_left_to_pay"],
             (float)$exittingResult->data["total_interest_to_pay"],
             (float)$exittingResult->data["cost_or_credit"],
             (float)$exittingResult->data["original_loan_amount"],);

            
              
              $loan_option_id = LoanOption::LowCost; $loan_parent_id = $loan->id;
             Loan::saveLoan($user->id,LoanCategory::Premium_Existing, $lender_id,$resultLowCost_Response->requiredMonthlyPayment,(float)$resultLowCost_Response->data["rate"], $resultLowCost_Response->totalAmountToPay, $downPayment, $creditScore,$loanAmount, $zipCode,$propretyTypeId,$loanTypeId, $useTypeId,
              $loan_option_id, $loan_parent_id, $propertyValue );

            $data = [
                "all_errors" => $errorArray,
                'section' => $section,
                        "sheet" => $sheet_type_Response,
                      $this->EXISTING_LOAN => $exittingResult->data,
                      $this->OPTIMAL_LOAN =>  $result_Response->data,
                      $this->LOW_RATE_LOAN =>  $resultOptimal_Response->data,  
                      $this->LOW_COST_LOAN => $resultLowCost_Response->data, 
                      $this->LENDER => $lender_Response,
                       "details" => $det];
                      // $this->NEW_LOAN => $result->data]; // WILL DELETE IN FUTURE
            return response()->json([$this->DATA => $data, $this->SUCCESS => true, $this->MSG => ""], 200);
          }
    }

    public function amortizationSchedule(Request $request)
    {
      $loan_amount = $request['loan_amount'];

      $rate = $request['rate'];
      $number_of_payments = $request['number_of_payments'];
      $data = $this->calculateAmotizationSchedule($loan_amount,$rate,$number_of_payments);
      return response()->json([$this->DATA => $data, $this->SUCCESS => true, $this->MSG => ""], 200);
    }
    
    private function isValidJumboLoan($sheet, $year){
        if($sheet != LoanSection::ConformingFixed && $sheet != LoanSection::HighBalanceFixed && $year != 30){
            return false;
        }
        return true;
    }
    
    private function isValidLoan($ltv, $propertyType, $typeOfUse){
        
        if($ltv > 75 && ($typeOfUse == LoanUseType::Investment || $typeOfUse == LoanUseType::Secondary) && ($propertyType == PropertyType::Two_Unit || $propertyType == PropertyType::Three_Unit || $propertyType == PropertyType::Four_Unit)){
              return false;
        }
        return true;
    }

}
