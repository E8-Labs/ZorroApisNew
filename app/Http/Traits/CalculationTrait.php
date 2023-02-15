<?php
namespace App\Http\Traits;
// use Illuminate\Http\JsonResponse;
// use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Models\Rate\Rate;
use App\Models\CreditScore;
use App\Models\CalculationResult;
use App\Models\Lender;
use App\Models\Types\LoanUseType;
use App\Models\Types\PropertyType as P_Type;
use App\Models\Loan\LoanSubSectionRow ;
use App\Models\Loan\LoanSubSection;
use App\Models\Loan\LoanSection;
use App\Models\Loan\LoanOption;
use App\Models\Loan\LoanCategory;
use App\Models\Loan\Loan;
use Carbon\Carbon;


trait CalculationTrait {
    
    function getYearTermValue($loan_type_id){
        if($loan_type_id == 1){ // 30 YR Fixed
            return 30;
        }
        if($loan_type_id == 2){
            return 15;
        }
        if($loan_type_id == 3){//Arm10_1Fixed
            return 101;
        }
        if($loan_type_id == 4){//Arm7_1Fixed
            return 71;
        }
        if($loan_type_id == 5){//Arm5_1Fixed
            return 51;
        }
        if($loan_type_id == 7){//15 YR Fixed
            return 15;
        }
        if($loan_type_id == 8){//10 YR Fixed
            return 10;
        }
        if($loan_type_id == 9){//25  YR Fixed
            return 25;
        }
    }

    function getSectionFromLoan($loan_amount, $property_type, $lender_id = 1){
    if($property_type == P_Type::Single_Family){
        if($loan_amount >= 100000 && $loan_amount <= 726200){
            return LoanSection::ConformingFixed;
        }
        else if($loan_amount > 726201 && $loan_amount <= 1089300){
            return LoanSection::HighBalanceFixed;
        }
        else if($loan_amount >= 1089301){
            if($lender_id == 1){
                return LoanSection::JmacManhattanJumbo;
            }
            return LoanSection::JMACJumboSmart;
        }
    }
    else if($property_type == P_Type::Two_Unit){
        if($loan_amount >= 100000 && $loan_amount <= 929850){
            return LoanSection::ConformingFixed;
        }
        else if($loan_amount > 929851 && $loan_amount <= 1394775){
            return LoanSection::HighBalanceFixed;
        }
        else if($loan_amount >= 1394776){
            if($lender_id == 1){
                return LoanSection::JmacManhattanJumbo;
            }
            return LoanSection::JMACJumboSmart;
        }
    }
    else if($property_type == P_Type::Three_Unit){
        if($loan_amount >= 100000 && $loan_amount <= 1123900){
            return LoanSection::ConformingFixed;
        }
        else if($loan_amount > 1123901 && $loan_amount <= 1685850){
            return LoanSection::HighBalanceFixed;
        }
        else if($loan_amount >= 1685851){
            if($lender_id == 1){
                return LoanSection::JmacManhattanJumbo;
            }
            return LoanSection::JMACJumboSmart;
        }
    }
    else if($property_type == P_Type::Four_Unit){
        if($loan_amount >= 100000 && $loan_amount <= 1396800){
            return LoanSection::ConformingFixed;
        }
        else if($loan_amount >= 1396801 && $loan_amount <= 2095200){
            return LoanSection::HighBalanceFixed;
        }
        else if($loan_amount >= 2095201){
            if($lender_id == 1){
                return LoanSection::JmacManhattanJumbo;//JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE;//
            }
            return LoanSection::JMACJumboSmart;
        }
    }
    else if($property_type == P_Type::Condos){
        // if($loan_amount >= 119999 && $loan_amount <= 1054500){
            // if($lender_id != 1){
            //     return LoanSection::JMACJumboSmart;
            // }
            return LoanSection::ConformingFixed;
        // }
        // else if($loan_amount >= 1054501 && $loan_amount <= 1581750){
        //     return LoanSection::HighBalanceFixed;
        // }
        // else if($loan_amount >= 1581751){
        //     return LoanSection::JmacManhattanJumbo;
        // }
    }

}
function multipleSubSectionSelectionNewRez($section,$propertyType,$typeOfUse, $ltv, $yearTerm, $loan_amount = 0, $loan_category = LoanCategory::Premium_Existing){
    // return "newrez";
    $sub = array();
    
    
        if($section == LoanSection::ConformingFixed){
            
            //ltv limit based on FICO (Credit Score Range)
            $ltvLimit =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other);
            array_push($sub, $ltvLimit);
            
            //Loan amount range
            $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit);
            array_push($sub, $loanLimit);
            
            //Year Range Add On
            $yearRangeAdon = new LoanSubSectionRow($section,LoanSubSection::YearRangeType, LoanSubSectionRow::YearRangeType);
            array_push($sub, $yearRangeAdon);
            
            //Second Home
            if($typeOfUse == LoanUseType::Secondary){
                $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
                array_push($sub, $secondHome);
            }
            //Investment
            if($typeOfUse == LoanUseType::Investment){
                $investment =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::RocketProInvestmentType );
                array_push($sub, $investment);
            }
            
        }
        
        if($section == LoanSection::HighBalanceFixed){
            
            //Loan amount range
            $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit);
            array_push($sub, $loanLimit);
            
            if ($propertyType == P_Type::Two_Unit){//2-4 Units
                $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
                array_push($sub, $condo);
            }
            if ($propertyType == P_Type::Condos){//Att. Condo w/LTV > 75% & term > 15 Yr
                $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
                array_push($sub, $condo);
            }
            
            if($typeOfUse == LoanUseType::Secondary){
                $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
                array_push($sub, $secondHome);
                
            }
        }
        
        if($section == LoanSection::JMACJumboSmart){
            
            //ltv limit based on FICO (Credit Score Range)
            $ltvLimit =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other);
            array_push($sub, $ltvLimit);
            
            //Loan amount range
            $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit);
            array_push($sub, $loanLimit);
            
            //2 Units
            if ($propertyType == P_Type::Two_Unit){//2 Units
                $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoUnits );
                array_push($sub, $condo);
            }
            //3-4 Units
            if ($propertyType == P_Type::Three_Unit || $propertyType == P_Type::Four_Unit){//3-4 Units
                $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::ThreeToFourUnits );
                array_push($sub, $condo);
            }
            
            //Condo
            if ($propertyType == P_Type::Condos){
                $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::CondoSimple );
                array_push($sub, $condo);
            }
            
            //Secondary
            if($typeOfUse == LoanUseType::Secondary){
                $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
                array_push($sub, $secondHome);
                
            }
            //Investment
            if($typeOfUse == LoanUseType::Investment){
                $investment =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::RocketProInvestmentType );
                array_push($sub, $investment);
            }
            
            //Year Range Add On
            $yearRangeAdon = new LoanSubSectionRow($section,LoanSubSection::YearRangeType, LoanSubSectionRow::YearRangeType);
            array_push($sub, $yearRangeAdon);
        }
      
      return $sub;
}

function multipleSubSectionSelectionHomePoint($section,$propertyType,$typeOfUse, $ltv, $yearTerm, $loan_amount = 0, $loan_category = LoanCategory::Premium_Existing){
    // return "HomePoint";
    // echo "HomePoint";
    $sub = array();
    
    

    //Loan Limit Adjustments
    
    if($section == LoanSection::ConformingFixed){
        // echo "ConformingFixed";
      $ltvLimit =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other);
      array_push($sub, $ltvLimit);
      
      //ARM Limits DU/LP ARM > 90 LTV (All Loan Amounts)
      if($yearTerm > 50){
            // Add Arms
            $armsLimit =  new LoanSubSectionRow($section,LoanSubSection::AllARMSType, LoanSubSectionRow::AllARMSType);
            array_push($sub, $armsLimit);
       }
      
      
      if($typeOfUse == LoanUseType::Investment){
            $investment =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::RocketProInvestmentType );
            array_push($sub, $investment);
        }

// echo  "LTV " . $ltv;
      if ($propertyType == P_Type::Condos && $yearTerm > 15 && $ltv > 75){
          $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
          array_push($sub, $condo);
      }
      
      if ($propertyType == P_Type::Two_Unit || $propertyType == P_Type::Three_Unit || $propertyType == P_Type::Four_Unit){
          $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
          array_push($sub, $condo);
        }
    
      if($typeOfUse == LoanUseType::Secondary){
        $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
        array_push($sub, $secondHome);
      }
      
      
      $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit);
        array_push($sub, $loanLimit);
        
        
        if($typeOfUse == LoanUseType::Primary){
        //HomePointLoanLimitPrimaryOnly
          $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::HomePointLoanLimitPrimaryOnly);
          array_push($sub, $loanLimit);
        }
        else{
            
        }
    }
    else if($section == LoanSection::HighBalanceFixed){
        // echo "HB " . $typeOfUse;
        $ltvLimit =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other);
      array_push($sub, $ltvLimit);
      
      //ARM Limits DU/LP ARM > 90 LTV (All Loan Amounts)
      if($yearTerm > 50){
            // Add Arms
            $armsLimit =  new LoanSubSectionRow($section,LoanSubSection::AllARMSType, LoanSubSectionRow::AllARMSType);
            array_push($sub, $armsLimit);
        }
      
      
      
      if($typeOfUse == LoanUseType::Investment){
            $investment =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::RocketProInvestmentType );
            array_push($sub, $investment);
        }

      if ($propertyType == P_Type::Condos && $yearTerm > 15 && $ltv > 75){
          $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
          array_push($sub, $condo);
      }
      
      if ($propertyType == P_Type::Two_Unit || $propertyType == P_Type::Three_Unit || $propertyType == P_Type::Four_Unit){
          $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
          array_push($sub, $condo);
        }
    
      if($typeOfUse == LoanUseType::Secondary){
        // echo "Add Secondary";
        $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
        array_push($sub, $secondHome);
      }
      
      
      $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit);
        array_push($sub, $loanLimit);
        
        
        if($typeOfUse == LoanUseType::Primary){
        //HomePointLoanLimitPrimaryOnly
          $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::HomePointLoanLimitPrimaryOnly);
          array_push($sub, $loanLimit);
        }
        else{
            
        }
      // $ltvLimit =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other);
      //   array_push($sub, $ltvLimit);
        
        
      //   //ARM Limits DU/LP ARM > 90 LTV (All Loan Amounts)
      // $armsLimit =  new LoanSubSectionRow($section,LoanSubSection::AllARMSType, LoanSubSectionRow::AllARMSType);
      // array_push($sub, $armsLimit);
    }
    
    if( $section == LoanSection::JMACJumboSmart){


      $yearRangeAdon = new LoanSubSectionRow($section,LoanSubSection::YearRangeType, LoanSubSectionRow::YearRangeType);
        array_push($sub, $yearRangeAdon);

        $ltvLimit =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other);
        array_push($sub, $ltvLimit);


        $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit);
        array_push($sub, $loanLimit);


        if($typeOfUse == LoanUseType::Investment){
            $investment =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::RocketProInvestmentType );
            array_push($sub, $investment);
        }
        if ($propertyType == P_Type::Condos){
          $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::CondoSimple );
          array_push($sub, $condo);
        }
        if ($propertyType == P_Type::Two_Unit){
          $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoUnits );
          array_push($sub, $condo);
        }

        if($typeOfUse == LoanUseType::Secondary){
            $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
            array_push($sub, $secondHome);
        }
    }

    return $sub;
}

function multipleSubSectionSelectionRocketPro($section,$propertyType,$typeOfUse, $ltv, $yearTerm, $loan_amount = 0, $loan_category = LoanCategory::Premium_Existing){
    //return "Rocket";
    $sub = array();
    if($section == LoanSection::HighBalanceFixed){
      // return "Rocket Pro Highg balance";
        // $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::HighBalanceAddOn);
        // array_push($sub, $loanLimit);


        // These are the same adjustments that are for Comforming Loans
        // $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit);
        // array_push($sub, $loanLimit);

        // These are the same adjustments that are for Comforming Loans
        $loanLimitHBPurchaseAndRate =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::RocketProHighBalancePurchaseRateAndTerm);
        array_push($sub, $loanLimitHBPurchaseAndRate);

        
        
        // return $sub;
    }

    if($section == LoanSection::HighBalanceFixed || $section == LoanSection::ConformingFixed){
        if($yearTerm > 50){
            // Add Arms
            $armsLimit =  new LoanSubSectionRow($section,LoanSubSection::AllARMSType, LoanSubSectionRow::AllARMSType);
            array_push($sub, $armsLimit);
        }
    }
    
    
    // RocketProLoanLimit
    $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit);
    array_push($sub, $loanLimit);
    
     $ltvLimit =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other);
    array_push($sub, $ltvLimit);
    
    if($typeOfUse == LoanUseType::Secondary){
        // echo "Second Home";
        // die();
        $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
        array_push($sub, $secondHome);
    }
    if($section == LoanSection::JMACJumboSmart){
        if($yearTerm == 30 && $typeOfUse == LoanUseType::Investment){
            $jumbo30YrInvestment =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Jumbo_30Y_Investment_Property );
            array_push($sub, $jumbo30YrInvestment);
        }
        if($loan_category == LoanCategory::Premium_Existing || $loan_category == LoanCategory::Free_Existing){
            $jumboRefi =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::RocketProRefinance );
            array_push($sub, $jumboRefi);
        }
        
    }
    
    
    if ($propertyType == P_Type::Condos && $yearTerm > 15 && $ltv > 75){
        $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
        array_push($sub, $condo);
    }
    if($propertyType == P_Type::Two_Unit || $propertyType == P_Type::Three_Unit || $propertyType == P_Type::Four_Unit){
        if($propertyType == P_Type::Two_Unit){
            $twounits =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoUnits );
            array_push($sub, $twounits);
        }
        else{
            $twounits =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::ThreeToFourUnits );
            array_push($sub, $twounits);
        }
        
    }
    
    if( $typeOfUse == LoanUseType::Investment  ){
        $investment =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::RocketProInvestmentType );
        array_push($sub, $investment);
    }
    return $sub;
    
}
function multipleSubSectionSelectionJmac($section,$propertyType,$typeOfUse, $ltv, $yearTerm, $loan_amount = 0, $loan_category = LoanCategory::Premium_Existing){
    $sub = array();
    if($section == LoanSection::HighBalanceFixed || $section == LoanSection::ConformingFixed){

        $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
        array_push($sub, $l);

        if($typeOfUse == LoanUseType::Secondary){
            $l =   new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_EQ_LESS_75 );
            array_push($sub, $l);
        
            $l =   new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_GREATER_75 );
            array_push($sub, $l);
        }
         
         if($propertyType == P_Type::Two_Unit || $propertyType == P_Type::Three_Unit || $propertyType == P_Type::Four_Unit){
            $l = new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
            array_push($sub, $l);
         }


        if($propertyType == P_Type::Condos){
            $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
            array_push($sub, $condo);
         }

         //Available in both
         $nooltv =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_EQ_LESS_75 );
         array_push($sub, $nooltv); 
        
    }
    if($section == LoanSection::ConformingFixed){
        $l = new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit );
        array_push($sub, $l);
    }
    if($section == LoanSection::HighBalanceFixed){
        if($yearTerm > 50){
            // Add Arms
            $armsLimit =  new LoanSubSectionRow($section,LoanSubSection::AllARMSType, LoanSubSectionRow::AllARMSType);
            array_push($sub, $armsLimit);
        }
        
    }
    if($section == LoanSection::JmacManhattanJumbo){
        $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
        array_push($sub, $l);
        if($typeOfUse == LoanUseType::Secondary){
            $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
            array_push($sub, $secondHome);
        }
        if($propertyType == P_Type::Two_Unit || $propertyType == P_Type::Three_Unit || $propertyType == P_Type::Four_Unit){
            // echo "Two Unit";
            $l = new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
            array_push($sub, $l);
        }
        if($propertyType == P_Type::Condos){
            $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
            array_push($sub, $condo);
        }
        if($yearTerm >= 50){
            // ARMS
            $armsLimit =  new LoanSubSectionRow($section,LoanSubSection::AllARMSType, LoanSubSectionRow::AllARMSType);
            array_push($sub, $armsLimit);
        }
    }
    if($section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
        $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
        array_push($sub, $l);

        $loanLimit =  new LoanSubSectionRow($section,LoanSubSection::LoanAmountType, LoanSubSectionRow::RocketProLoanLimit);
        array_push($sub, $loanLimit);

        if($typeOfUse == LoanUseType::Secondary){
            $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
            array_push($sub, $secondHome);
        }
        if($propertyType == P_Type::Two_Unit || $propertyType == P_Type::Three_Unit || $propertyType == P_Type::Four_Unit){
            $l = new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
            array_push($sub, $l);
        }
        if($propertyType == P_Type::Condos){
            echo "This is condo type";
            $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
            array_push($sub, $condo);
        }
        if( $typeOfUse == LoanUseType::Investment  ){
            $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Jumbo_30Y_Investment_Property );
            array_push($sub, $condo);
        }

    }
    return $sub;
}

function multipleSubsectionsSelection($section,$propertyType,$typeOfUse, $ltv, $yearTerm, $loan_amount = 0, $loan_category = LoanCategory::Premium_Existing, $lender = 1){
    
    //  return $lender;
    if($lender == 4){
        // return "NewRez is active";
        return $this->multipleSubSectionSelectionNewRez($section,$propertyType,$typeOfUse, $ltv, $yearTerm, $loan_amount, $loan_category);
    }
    if($lender == 2){
        // return "Rocket Pro is active";
        return $this->multipleSubSectionSelectionRocketPro($section,$propertyType,$typeOfUse, $ltv, $yearTerm, $loan_amount, $loan_category);
    }
    if($lender == 3){
        // return "Greenhouse is active";
        return $this->multipleSubSectionSelectionHomePoint($section,$propertyType,$typeOfUse, $ltv, $yearTerm, $loan_amount, $loan_category);
    }
    if($lender == 1){
        // return "JMAC";
        // return "Greenhouse is active";
        return $this->multipleSubSectionSelectionJmac($section,$propertyType,$typeOfUse, $ltv, $yearTerm, $loan_amount, $loan_category);
    }
    // return "Hello JMAC";
  if($propertyType == P_Type::Single_Family){
    //   return "Single Family" . $yearTerm;
    $sub = array();
    if( $typeOfUse == LoanUseType::Primary  ){
        // return "Single Family Primary " . $section . " ltv " . $ltv;
        // $i = 0;
      if($section == LoanSection::JmacManhattanJumbo || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
        if($ltv > 80){
          return NULL;
        }
        // return "Yes";
        if($yearTerm == 30){
          $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
          array_push($sub, $l);

          // if($section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
          //     $purchase =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SecondHomeLagunaJumbo );
          //     array_push($sub, $purchase);
          // }
          if($loan_amount > LoanSubSectionRow::JumboLoanAmountGreaterThan1Million){
              $purchase =  new LoanSubSectionRow($section, LoanSubSection::LoanAmountType, LoanSubSectionRow::LagunaLoanAmountCondition);
              // echo "Apply gr than 1 mil" . $section;
              array_push($sub, $purchase);
          }

          // Apply Purchase row if can be applied
        //   if($loan_category == LoanCategory::Premium_New){
        //         $purchase =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Purchase );
        //         array_push($sub, $purchase);
        //   }
          



        }
        else{
          return NULL;
        }
      }
        else if($yearTerm > 15){ // and ltv
        // return "Single Family Primary Term > 15";
                $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
                // $sub[$i] = $l;
                array_push($sub, $l);
                return $sub;
                // $i ++;
        }
        // echo "JMAC";
        // None applies so just add dummy to get section and sub section values for this
        $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
        array_push($sub, $l);
        return $sub;
    }
    else if($typeOfUse == LoanUseType::Secondary){
        // $i = 0;
        // return "Single Family Secondary";
      if($section == LoanSection::JmacManhattanJumbo || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
        if($ltv > 80){
          return NULL;
        }
        if($yearTerm == 30){
          $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
          array_push($sub, $l);

          

          if($section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
              $purchase =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SecondHomeLagunaJumbo );
              array_push($sub, $purchase);
          }
          else{ // Manhattan Jumbo
            $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
            array_push($sub, $secondHome);
          }
          // if loan is greater than 1 million
          if($loan_amount > LoanSubSectionRow::JumboLoanAmountGreaterThan1Million){
              $purchase =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::LagunaLoanAmountCondition );
              array_push($sub, $purchase);
          }

          //Apply Purchase row if can be applied
          if($loan_category == LoanCategory::Premium_New){
                $purchase =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Purchase );
                array_push($sub, $purchase);
            }

        }
        else{
          return NULL;
        }
      }
      else if( $ltv <= 85   ){
          $l =   new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_EQ_LESS_75 );
        //   $sub[$i] = $l;
          array_push($sub, $l);
        //   $i++;
        }
        if( $ltv > 85){
          $l =   new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_GREATER_75 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
        //   $i++;
        }
    }

    else if( $typeOfUse == LoanUseType::Investment  ){
        // return "Single Family Investment";
      if($section == LoanSection::JmacManhattanJumbo || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
        if($ltv > 80){
          return NULL;
        }
        if($yearTerm == 30){
          $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
          array_push($sub, $l);

          // if loan is greater than 1 million
          if($loan_amount > LoanSubSectionRow::JumboLoanAmountGreaterThan1Million){
              $purchase =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::LagunaLoanAmountCondition );
              array_push($sub, $purchase);
          }

          //Apply Purchase row if can be applied
          if($loan_category == LoanCategory::Premium_New){
                $purchase =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Purchase );
                array_push($sub, $purchase);
            }

        }
        else{
          return NULL;
        }
      }
        else if( $ltv <= 75   ){
            $l =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_EQ_LESS_75 );
        //   $sub[$i] = $l;
            array_push($sub, $l);
        //   $i++;
        }
        else if( $ltv >= 75.1 && $ltv <= 80  ){
          $l =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_75_to_80 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
        //   $i++;
        }
        else if( $ltv >= 65 ){
          $l =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_EQ_GREATER_65 );
        //   $sub[$i] = $l;
            array_push($sub, $l);
        //   $i++;
        }
      }
    //   return "JMAC HELLO";
      return $sub;
  }
//   return "Single Family";
  //Single Family Ends here

   else if($propertyType == P_Type::Two_Unit || $propertyType == P_Type::Three_Unit || $propertyType == P_Type::Four_Unit){
    //   return "Single Family" . $yearTerm;
    $sub = array();


    if($section == LoanSection::JmacManhattanJumbo || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
        if($ltv > 80){
          return NULL;
        }
        if($yearTerm == 30){
          $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
          array_push($sub, $l);

        if($section == LoanSection::JmacManhattanJumbo){
            if($loan_category == LoanCategory::Premium_New){
                $purchase =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Purchase );
                array_push($sub, $purchase);
            }
            
            
            if($typeOfUse == LoanUseType::Secondary){
                $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
                array_push($sub, $secondHome);
            }
            
            $l = new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
            array_push($sub, $l);
        }
        else if ($section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
            if($loan_amount > LoanSubSectionRow::JumboLoanAmountGreaterThan1Million){
              $purchase =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::LagunaLoanAmountCondition );
              array_push($sub, $purchase);
              
            }

            // $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SecondHomeLagunaJumbo );
            //   array_push($sub, $secondHome);
        }

        }
        else{
          return NULL;
        }
      }
      else{
        $l = new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
        array_push($sub, $l);
      }
    //applies for all i think
        


    // if( $typeOfUse == LoanUseType::Primary  ){
    //     // return "Single Family Primary";
    //     // $i = 0;
    //     if($yearTerm > 15){ // and ltv
    //     // return "Single Family Primary Term > 15";
    //             $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
    //             // $sub[$i] = $l;
    //             array_push($sub, $l);
    //             // $i ++;
    //     }
    // }
     if($typeOfUse == LoanUseType::Secondary){
        if($section == LoanSection::JmacManhattanJumbo || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
            // already added conditions above. Just to make sure that below conditions don't execute
        }
        else if( $ltv <= 85   ){
          $l =   new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_EQ_LESS_75 );
        //   $sub[$i] = $l;
          array_push($sub, $l);
        //   $i++;
        }
        else if( $ltv > 85){
          $l =   new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_GREATER_75 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
        //   $i++;
        }
    }

    else if( $typeOfUse == LoanUseType::Investment  ){
        // return "Single Family Investment";
      
       if( $ltv <= 75   ){
          $l =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_EQ_LESS_75 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
        //   $i++;
        }
        else if( $ltv >= 75.1 && $ltv <= 80  ){
          $l =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_75_to_80 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
        //   $i++;
        }
        else if( $ltv >= 65 ){
          $l =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_EQ_GREATER_65 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
          $i++;
        }
      }
      return $sub;
  } // ends 2-4 units
  else if ($propertyType == P_Type::Condos){ // Condos
      $sub = array();
          

    if($section == LoanSection::JmacManhattanJumbo || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
        if($ltv > 80){
            return NULL;
            }
        if($yearTerm == 30){
          // $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
          // array_push($sub, $l);
          // if($section == LoanSection::JmacManhattanJumbo){
          //     $condoSimple =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::CondoSimple);
          //     array_push($sub, $condoSimple);
          // }
          
          // if loan is greater than 1 million
          if($loan_amount > LoanSubSectionRow::JumboLoanAmountGreaterThan1Million){
              $purchase =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::LagunaLoanAmountCondition );
              array_push($sub, $purchase);
          }

          //Apply Purchase row if can be applied
          // $purchase =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Purchase );
          // array_push($sub, $purchase);

        }
        else{
          return NULL;
        }
    }
        

      if($typeOfUse == LoanUseType::Primary){
        $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
            array_push($sub, $l);
            
            if($yearTerm > 15 && $ltv > 75){
                $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
                array_push($sub, $condo);
            }
      }
     else if($typeOfUse == LoanUseType::Secondary){
        // $i = 0;
        // return "Single Family Secondary";
      if($section == LoanSection::JmacManhattanJumbo || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
        if($ltv > 80){
          return NULL;
        }
        if($yearTerm == 30){
          $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
          array_push($sub, $l);


        if($section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
              $purchase =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SecondHomeLagunaJumbo );
              array_push($sub, $purchase);
          }
          else{ // Manhattan Jumbo
            $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::Occ_2ndHome );
            array_push($sub, $secondHome);
          }
          

          

          $condoSimple =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::CondoSimple);
          array_push($sub, $condoSimple);
          //Apply Purchase row if can be applied
          // $purchase =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Purchase );
          // array_push($sub, $purchase);

        }
        else{
          return NULL;
        }
      }
      if($yearTerm > 15 && $ltv > 75){
                $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
                array_push($sub, $condo);
        }
      else if( $ltv <= 85   ){
          $l =   new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_EQ_LESS_75 );
        //   $sub[$i] = $l;
          array_push($sub, $l);
        //   $i++;
        }
        if( $ltv > 85){
          $l =   new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_GREATER_75 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
        //   $i++;
        }
    }

    else if( $typeOfUse == LoanUseType::Investment  ){
        // return "Single Family Investment";
        if($yearTerm > 15 && $ltv > 75){
                $condo =  new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
                array_push($sub, $condo);
            }
        
      if($section == LoanSection::JmacManhattanJumbo || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE || $section == LoanSection::JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE){
        if($ltv > 80){
          return NULL;
        }
        if($yearTerm == 30){
          $l =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
          array_push($sub, $l);

          $condoSimple =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::CondoSimple);
          array_push($sub, $condoSimple);


          // if loan is greater than 1 million
          if($loan_amount > LoanSubSectionRow::JumboLoanAmountGreaterThan1Million){
              $purchase =  new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::LagunaLoanAmountCondition );
              array_push($sub, $purchase);
          }

          // $secondHome =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Occ_2ndHome );
          // array_push($sub, $secondHome);
          //Apply Purchase row if can be applied
          // $purchase =  new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Purchase );
          // array_push($sub, $purchase);

        }
        else{
          return NULL;
        }
      }
      
        else if( $ltv <= 75   ){
          $l =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_EQ_LESS_75 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
        //   $i++;
        }
        else if( $ltv >= 75.1 && $ltv <= 80  ){
          $l =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_75_to_80 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
        //   $i++;
        }
        else if( $ltv >= 65 ){
          $l =    new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_EQ_GREATER_65 );
        //   $sub[$i] = $l;
        array_push($sub, $l);
          $i++;
        }
      }
      return $sub;
  

}
}
    function subSectionSelection($section,$propertyType,$typeOfUse, $ltv, $yearTerm ){

    //return new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
      // Property Type
      if( $typeOfUse == LoanUseType::Primary  ){
        //   if($propertyType == P_Type::Single_Family){
        //     //   return "Single Family Primary " . $section;
        //     return new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Single_Family );
        //     }
        if( $propertyType == P_Type::Two_Unit || $propertyType == P_Type::Three_Unit || $propertyType == P_Type::Four_Unit   ){
            return new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::TwoToFourUnits );
          }

        if( $propertyType == P_Type::Condos && $ltv > 75 && $yearTerm > 15  ){
            return new LoanSubSectionRow($section,LoanSubSection::PropertyType, LoanSubSectionRow::Condo );
          }
      }

        /// Occupancy
      if( $typeOfUse == LoanUseType::Investment  ){
        if( $ltv <= 75   ){
          return new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_EQ_LESS_75 );
        }
        if( $ltv >= 75.1 && $ltv <= 80  ){
          return new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_75_to_80 );
        }
        if( $ltv >= 65 ){
          return new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::NOO_LTV_EQ_GREATER_65 );
        }
      }

      if( $typeOfUse == LoanUseType::Secondary  ){
        if( $ltv <= 75   ){
          return new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_EQ_LESS_75 );
        }
        if( $ltv > 75){
          return new LoanSubSectionRow($section,LoanSubSection::Occupancy, LoanSubSectionRow::SECOND_HOME_LTV_GREATER_75 );
        }
      }

        return new LoanSubSectionRow($section,LoanSubSection::Others, LoanSubSectionRow::Other );
      }
    function sectionSelection(){
      
    }

    function getLender($id){
          return Lender::select("id","name","logo_url","website")->where("id", $id)->first();
    }

    function validatePremiumLoanRequest($creditScore,$downPayment, $loanAmount ){
        if( $creditScore  < 600 || $creditScore  > 840 )
              return "Credit Score must be in 600 to 840 range";
        if( $downPayment > $loanAmount )
              return "Down payment must be less than Loan Amount";
        if( $loanAmount  < 1 )
              return "Loan Amount must be greater then 0";
        return "true";
    }

    function getExistingLoan($lender_id,$ltv,$monthly_private_mortgage_insurance = 0,$creditScore,$loan_amount,$numberOfPayments, $existingRate, $momthlyMortgage,$loan_start_date ){

            $result = new CalculationResult;
            $result->annualLowestRate = $existingRate;
            $result->monthlyPaymentsRate =  ($result->annualLowestRate/100) / 12 ;
            $result->requiredMonthlyPayment = $momthlyMortgage;
          //$result->requiredMonthlyPayment = $this->calculateMonthlyPayment($loan_amount, $numberOfPayments, $result->monthlyPaymentsRate );
            $result->totalAmountToPay = $result->requiredMonthlyPayment * $numberOfPayments;
            $result->totalInterestToPay = $result->totalAmountToPay -  $loan_amount;
            $result->calculationDetail = $this->getDetailData($result->creditScore,$loan_amount,$numberOfPayments,$result->annualLowestRate,$result->monthlyPaymentsRate,$result->lowestRate);
            if($loan_start_date!=null)
            {
              $amortizationSchedule = $this->calculateAmotizationSchedule($loan_amount,$result->annualLowestRate,$loan_start_date);
              $totalAmount = abs($this->roundFloat($result->totalInterestToPay)) + $loan_amount;
            //   $interestLeft = abs($this->roundFloat($result->totalInterestToPay)) - 0;
              $result->data = [
                            "private_mortgage_insurance" => $monthly_private_mortgage_insurance,
                            "number_of_payments_till_date" => $amortizationSchedule['number_of_payments_till_date'],
                            "interest_paid_till_date" => $amortizationSchedule['interest_paid_till_date'],
                            "Interest_left_to_pay" =>  abs($result->totalInterestToPay)-$amortizationSchedule['interest_paid_till_date'],
                            "required_monthly_payment" => $this->roundFloat($result->requiredMonthlyPayment) ,
                             "rate" =>   $this->roundFloat($result->annualLowestRate),
                             "total_amount_to_pay" => $this->roundFloat($totalAmount) ,
                             "total_interest_to_pay" =>  abs($this->roundFloat($result->totalInterestToPay)),
                             "loan_amount" =>  $this->roundFloat($loan_amount),
                             "cost_or_credit" => 0,
                             // "calculation_detail" => $result->calculationDetail 
                         ] ;
            }
            else
            {
              $result->data = [
                "private_mortgage_insurance" => $monthly_private_mortgage_insurance,
                "required_monthly_payment" => $this->roundFloat($result->requiredMonthlyPayment) ,
                 "rate" =>   $this->roundFloat($result->annualLowestRate),
                 "total_amount_to_pay" => $this->roundFloat($result->totalAmountToPay) ,
                 "total_interest_to_pay" =>  abs($this->roundFloat($result->totalInterestToPay)),
                 "loan_amount" => $this->roundFloat($loan_amount),
                 "cost_or_credit" => 0,
                 // "calculation_detail" => $result->calculationDetail 
             ] ;
            }
          
            return $result;
   }

//LoanCategory::Premium_New
    function getLoan($lender_id,$ltv,$creditScore,$loan_amount,$numberOfPayments, $existingRate, $section, $year = 30, $loan_category, $sheet_type ){

            $result = new CalculationResult;
            if($existingRate == -1 ){ // new loan
                // $result->ltv = $this->getLtvToValue($ltv);
                // $result->creditScore = $this->getCreditScore( $creditScore , $result->ltv , $lender_id, $section );
                $result->creditScore = $this->getCreditScoreWithSection( $creditScore , $ltv , $lender_id , $section, $loan_amount, $loan_category, $year);
                // return $result->creditScore;
                $result->rates = $this->getRatesWithSection($result->creditScore, $lender_id, $section, $year_type_id = 1  );//$this->getRates($result->creditScore,$lender_id );
                $result->lowestRate = $this->getValueClosest(0, $result->rates, $loan_amount);
                $result->annualLowestRate = $result->lowestRate->rate;
                
            }else{
                $result->annualLowestRate = $existingRate;  //$result->lowestRate->rate;

            }
            // return ["annualLow" => $result->lowestRate, 'credit' => $result->creditScore];
                $result->monthlyPaymentsRate =  ($result->annualLowestRate/100) / 12 ;
                $result->requiredMonthlyPayment = $this->calculateMonthlyPayment($loan_amount, $numberOfPayments, $result->monthlyPaymentsRate );
                $result->totalAmountToPay = $result->requiredMonthlyPayment * $numberOfPayments;
                $result->totalInterestToPay = $result->totalAmountToPay -  $loan_amount;
                $result->calculationDetail = $this->getDetailData($result->creditScore,$loan_amount,$numberOfPayments,$result->annualLowestRate,$result->monthlyPaymentsRate,$result->lowestRate);
                $result->data = ["required_monthly_payment" => $this->roundFloat($result->requiredMonthlyPayment) ,
                             "rate" =>   $this->roundFloat($result->annualLowestRate),
                             "total_amount_to_pay" => $this->roundFloat($result->totalAmountToPay) ,
                             "total_interest_to_pay" =>  $this->roundFloat($result->totalInterestToPay),
                             "loan_amount" => $this->roundFloat($loan_amount),
                             "cost_or_credit" => 0,
                             // "calculation_detail" => $result->calculationDetail 
                         ] ;
            return $result;
   }

       function getLoanWithSection($section,$lender_id,$ltv,$monthly_private_mortgage_insurance,$creditScore,$loan_amount,$numberOfPayments, $existingRate ,$loan_start_date,  $downPayment = 0 ,$costOrCredit, $loan_type_id = 1, $loan_category, $year){
            // $costOrCredit = $costOrCredit;
        // return "here";
            $result = new CalculationResult;
            $actual_loan_amount = $loan_amount - $downPayment;
            if($existingRate == -1 ){ // new loan
            
                $result->ltv = $this->getLtvToValue($ltv);
                $result->creditScore = $this->getCreditScoreWithSection( $creditScore , $result->ltv , $lender_id , $section, $actual_loan_amount, $loan_category, $year);
                // echo json_encode(["credit" => $result->creditScore, "sec"=> $section]);
                // die();
                $csValue = 0;
                foreach($result->creditScore as $cs){
                    if(isset($cs->value)){
                        if($cs->value < -900){
                            
                            return ["status"=> false, "message" => "This loan is invalid for ltv " . $cs->ltv_from . " - " . $cs->ltv_to . " and credit score " . $cs->cs_from . " - " . $cs->cs_to . " and loan amount " . $cs->loan_from . " - " . $cs->loan_to . ". Please update your credit score and ltv to get the results." ];
                        }
                        $csValue += $cs->value;
                        
                    }
                    
                }
                // echo $csValue;
                // die();
                // return $result->creditScore;
                
                // echo $result->creditScore;
                // return response()->json([$this->DATA => $result->creditScore, $this->SUCCESS => true, $this->MSG => ""], 200);
                $result->rates = $this->getRatesWithSection($result->creditScore,$lender_id, $section, $loan_type_id );
                // echo json_encode($result->rates);
                // die();
                if(count($result->rates) == 0){
                    return ["status"=> false, "message" => "Loan is invalid. No rates."];
                }
                $result->lowestRate = $this->getValueClosest(0, $result->rates, $actual_loan_amount);
                
                // echo $result->lowestRate;
                // die();
                ///////////////////////////////////////////////////////////////////////////////////////
                
                $result->optimal_costOrCredit = (($result->lowestRate->value) * ( $actual_loan_amount  ))/100;
                $result->optimal_rate = $result->lowestRate->rate;
                //changing this fixed the issue for low rate and low cost for rocket pro. It does
                //  $result->rates = array_reverse($result->rates);

                $this->calculateLowRate($result, $result->rates, $result->optimal_rate, $actual_loan_amount);
                // $temp_low_rate_costOrCredit = $result->low_rate_costOrCredit;
                // // echo $temp_low_rate_costOrCredit;
                // if($result->low_rate_costOrCredit < 0 ){
                //     $temp_low_rate_costOrCredit *= -1;
                // }
                // while($temp_low_rate_costOrCredit < 3000){
                //     echo "Calculating again";
                //     $this->calculateLowRate($result, $result->rates, $result->optimal_rate, $actual_loan_amount);
                //     $temp_low_rate_costOrCredit = $result->low_rate_costOrCredit;
                // // echo $temp_low_rate_costOrCredit;
                //     if($result->low_rate_costOrCredit < 0 ){
                //         $temp_low_rate_costOrCredit *= -1;
                //     }
                // }
                // echo "Cost/Credit " . $result->low_rate_costOrCredit;
                $this->calculateOptimal($result, $result->rates, $result->low_rate_rate, $actual_loan_amount );
                $this->calculateLowCost($result, $result->rates, $result->optimal_rate, $actual_loan_amount );

                //  return ["lowest" => $result->lowestRate->value, "loan" => $actual_loan_amount, "loanpar" => $loan_amount, "down" => $downPayment, "cost_or_credit" => $result->low_cost_costOrCredit, "rate" => $result->lowestRate->rate, "lender" => $lender_id, "section" => $section, "credit" => $result->creditScore, "rates" => $result->rates, "Total Addon" => $csValue];

                // if($loanOption = LoanOption::Optimal){
                  $costOrCredit =  $result->optimal_costOrCredit;
                // }else
                // if($loanOption = LoanOption::LowRate){
                //    $costOrCredit =  $result->low_rate_costOrCredit;
                // }else
                // if($loanOption = LoanOption::LowCost){
                //    $costOrCredit =  $result->low_cost_costOrCredit;
                // }


                // return  $result;
                /////////////////////////////////////////////////////////////////////////
                  // echo $result->optimal_rate;
                  // die();
                $result->annualLowestRate = $result->optimal_rate;  //$result->lowestRate->rate;
                // return $result;
            }else{
                // echo "Existing";
                $result->annualLowestRate = $existingRate;  //$result->lowestRate->rate;

            }

                $result->monthlyPaymentsRate =  ($result->annualLowestRate/100) / 12 ;
                // echo "Lowest" . $result->monthlyPaymentsRate;
                // $result->data = ["monthlyrate" => $result->monthlyPaymentsRate ];
                // return $result;
                $result->requiredMonthlyPayment = $this->calculateMonthlyPayment($actual_loan_amount, $numberOfPayments, $result->monthlyPaymentsRate );
                $result->totalAmountToPay = $result->requiredMonthlyPayment * $numberOfPayments;
                $result->totalInterestToPay = $result->totalAmountToPay -  $actual_loan_amount;
                $result->calculationDetail = $this->getDetailData($result->creditScore,$actual_loan_amount,$numberOfPayments,$result->annualLowestRate,$result->monthlyPaymentsRate,$result->lowestRate , $section);
                if($loan_start_date!=null)
                {
                  $amortizationSchedule = $this->calculateAmotizationSchedule($actual_loan_amount,$result->annualLowestRate,$loan_start_date);
                  $result->data = [
                    "private_mortgage_insurance" => $monthly_private_mortgage_insurance,
                    "number_of_payments_till_date" => $amortizationSchedule['number_of_payments_till_date'],
                    "interest_paid_till_date" => $amortizationSchedule['interest_paid_till_date'],
                    "Interest_left_to_pay" =>  abs($result->totalInterestToPay-$amortizationSchedule['interest_paid_till_date']),
                    "required_monthly_payment" =>  $this->roundFloat($result->requiredMonthlyPayment) ,
                    "rate" =>    $this->roundFloat($result->annualLowestRate),
                    "total_amount_to_pay" =>  $result->totalInterestToPay + $actual_loan_amount ,//$this->roundFloat($result->totalAmountToPay) ,
                    "total_interest_to_pay" =>  $this->roundFloat($result->totalInterestToPay),
                    "loan_amount" =>  $this->roundFloat($actual_loan_amount),
                    "cost_or_credit" => $costOrCredit,
                    "all_rates" => $result->rates,
                    "monthly_payments_rate" => $result->monthlyPaymentsRate,
                    "annual_lowest_rate" => $result->annualLowestRate / 100,
                    "monthly_payments_rate_calculation_formulae" => $this->calculateMonthlyPaymentString($actual_loan_amount, $numberOfPayments, $result->monthlyPaymentsRate) 
                    // "calculation_detail" => $result->calculationDetail
                  ];
                }
                else
                {
                  $result->data = [
                    "private_mortgage_insurance" => $monthly_private_mortgage_insurance,
                    "required_monthly_payment" => $this->roundFloat($result->requiredMonthlyPayment) ,
                    "rate" =>   $this->roundFloat($result->annualLowestRate),
                    "total_amount_to_pay" => $this->roundFloat($result->totalAmountToPay) ,
                    "total_interest_to_pay" =>  $this->roundFloat($result->totalInterestToPay),
                    "loan_amount" => $this->roundFloat($actual_loan_amount),
                    "cost_or_credit" => $costOrCredit,
                    // "calculation_detail" => $result->calculationDetail,
                    "all_rates" => $result->rates,
                    "monthly_payments_rate" => $result->monthlyPaymentsRate,
                    "annual_lowest_rate" => $result->annualLowestRate / 100,
                    "monthly_payments_rate_calculation_formulae" => $this->calculateMonthlyPaymentString($actual_loan_amount, $numberOfPayments, $result->monthlyPaymentsRate) 

                ];


                }

            return $result;
   }

    function calculateLowRate($result, $rates, $optimal_rate, $actual_loan_amount ){ //  $actual_loan_amount = $loan_amount - $downPayment;
        // echo "Rate Closest To Zero " . $optimal_rate;
        // die();
        $csValue = 0;
        foreach($result->creditScore as $cs){
            if(isset($cs->value)){
                $csValue += $cs->value;
            }
        }
    
    // foreach($rates as $rate){
    //     echo $rate;
    //     echo "\n";
    // }

      $previousRate = $rates[0];
      // $nextRate = $rates[0];
      for ($i=0; $i < count($rates) ; $i++) {
                // echo "Running for Rate " . $rates[$i]->rate . " ";
          if($rates[$i]->rate <= $optimal_rate){ // <= the closest to zero value so we don't get values above that.
            // if( $i > 0 ){
            $previousRate = $rates[$i];
            // }
            // if(($i + 1) < count($rates)){
            //   $nextRate = $rates[$i + 1];
            // }
            // echo "Break : "  . $previousRate;
            // die();
            $result->low_rate_costOrCredit = (($previousRate->value) * ( $actual_loan_amount  ))/100;
            $result->low_rate_rate = $previousRate->rate;
            // echo "Value for rate " . $previousRate->rate . " is " . $result->low_rate_costOrCredit;
            $temp_low_rate_costOrCredit = $result->low_rate_costOrCredit;
            // echo $temp_low_rate_costOrCredit;
            if($result->low_rate_costOrCredit < 0 ){
              $temp_low_rate_costOrCredit *= -1;
            }
        // echo "Rate " . $previousRate->rate . " gives " . $temp_low_rate_costOrCredit . "\n";
            if($temp_low_rate_costOrCredit <= 3000 ) {
              // echo "gr th 3000";
            
                // $result->low_rate_costOrCredit = $result->optimal_costOrCredit;
                // $result->low_rate_rate = $result->optimal_rate;
        
        
                // $result->optimal_costOrCredit = (($nextRate->value) * ( $actual_loan_amount  ))/100;
                // $result->optimal_rate = $nextRate->rate;
        // echo "Temp Cost Credit " . $temp_low_rate_costOrCredit;
        // die();
                break;
            }
            // break;
          }
      }
      // echo $previousRate;
      // echo $nextRate;
      // echo json_encode($rates);
      // die();
      // echo $optimal_rate;
      //question here. Do we add all adjustments and then multiply that with loan amount or what?
      

      // else{
      //    // if it is lower than 3000
      //       $this->calculateLowRate($result, $rates, $previousRate->value, $actual_loan_amount );
      // }
    }

    function calculateOptimal($result, $rates, $low_rate, $actual_loan_amount ){ //  $actual_loan_amount = $loan_amount - $downPayment;
        // echo $low_rate;
        // die();
        $csValue = 0;
        foreach($result->creditScore as $cs){
            if(isset($cs->value)){
                $csValue += $cs->value;
            }
        }
      $nextRate = $rates[0];
      for ($i=0; $i < count($rates) ; $i++) {

          if($rates[$i]->rate == $low_rate){
            // echo  "rates[" . $i . "] = " . $low_rate;
            if(($i + 1) < count($rates)){
              $nextRate = $rates[$i + 1];
            }else{
              $nextRate = $rates[$i];
            }
            break;
          }
      }
// echo json_encode(["next" => $nextRate, "low" => $low_rate, "rates" => $rates]);
// die();
      $result->optimal_costOrCredit = (($nextRate->value) * ( $actual_loan_amount  ))/100;
      $result->optimal_rate = $nextRate->rate;

    }

   function calculateLowCost($result, $rates, $optimal_rate, $actual_loan_amount ){ //  $actual_loan_amount = $loan_amount - $downPayment;
        $csValue = 0;
        foreach($result->creditScore as $cs){
            if(isset($cs->value)){
                $csValue += $cs->value;
            }
        }
      $nextRate = $rates[0];
      for ($i=0; $i < count($rates) ; $i++) {

          if($rates[$i]->rate == $optimal_rate){
            if(($i + 1) < count($rates)){
              $nextRate = $rates[$i + 1];
            }else{
              $nextRate = $rates[$i];
            }
            break;
          }
      }

      $result->low_cost_costOrCredit = (($nextRate->value) * ( $actual_loan_amount  ))/100;
      $result->low_cost_rate = $nextRate->rate;

    }

   function getDetailData($creditScore,$loan_amount,$numberOfPayments,$annualLowestRate,$monthlyPaymentsRate, $lowestRate , $section = null ){

        // if( $section != null){
        //   $sectionDetails = $section->detail();
        // }else{
        //   $sectionDetails = "";
        // }

        return [
               "section_details" => $section,//$sectionDetails,
               "loan_Amount(a)" => $loan_amount ,
               "number_of_payments(n)" => $numberOfPayments,
               "annual_lowest_rate(ALR)" => $this->roundFloat($annualLowestRate),
               "monthly_payments_rate (r = (ALR/100)/12 )" => $this->roundFloat($monthlyPaymentsRate),
               "lowest_rate" => $lowestRate,
               "credit_score" => $creditScore
             ];
    }


  function getRates($creditScore, $lender_id ){ // here I have to inject subsection and section
        $rates = Rate::select("rate", "value" )->where("lender_id", $lender_id)->where('day_type_id', 3)->get()
                 ->map(function ($rate) use($creditScore) {
                                    $rate->value = $rate->value + $creditScore->value;
                                    return $rate;
                                })
                  ->sortBy('value');
        return $rates->values()->all();
   }

  function getRatesWithSection($creditScore, $lender_id, $sections, $year_type_id = 1  ){ // here I have to inject subsection and section
    // if($section->subSection == LoanSubSection::Others ){
    $section = $sections[0];
    $csValue = 0;
    // deducts a 100 from the rocket pro sheet
    $deduct = 0;
    if($lender_id == 2 || $lender_id == 3){
        $deduct = 100;
    }
    foreach($creditScore as $cs){
        $csValue += $cs->value;
    }
    // echo json_encode(["Adon" => $csValue, "Credit" => $creditScore, "sec" => $sections]);
    // die();
        $rates = Rate::select("rate", "value", "id" )->where("lender_id", $lender_id)->where('day_type_id', 3)
        ->where("loan_section_id", $section->section)->where('year_type_id', $year_type_id)
        // ->where("loan_sub_section_id", $section->subSection)
        ->get()->map(function ($rate) use($csValue, $deduct, $lender_id) {
            $rate->valueBeforAdon = $rate->value;
                                    if($lender_id == 2 || $lender_id == 3){
                                        $rate->value = ($deduct - $rate->value) + $csValue;//
                                    }
                                    else{
                                        $rate->value = ($rate->value - $deduct) + $csValue;//
                                    }

                                    $rate->adon = $csValue;
                                    return $rate;
                                })
                  ->sortBy('rate');
        return $rates->values()->all();
      // }
   }

    function getCreditScore($creditScore,$ltv,$lender_id ){ // here I have to inject subsection and section
    return CreditScore::where("cs_from",$creditScore)->where("ltv_to", $ltv )->where("lender_id", $lender_id)->first();
   }
    function getCreditScoreWithSection($creditScore,$ltv,$lender_id, $sections, $loan_amount, $loan_category, $year ){
$scores = [];
      
      


     foreach($sections as $section){
        //  echo  $section->subSection;
              if($section->subSection == LoanSubSection::PropertyType || $section->subSection == LoanSubSection::Occupancy  ){
                // echo "sec" . $section->row;
                  // echo "Occupancy = sec " . $section->row . " sub " . $section->subSection . " det " . $section->row . "Loan Category " . $loan_category . "\n";
          $cs = CreditScore::
                             where("cs_from", '<=',$creditScore)
                             ->where("cs_to", '>=',$creditScore)
                            // ->where("ltv_to", $ltv )
                            ->where("detail", 'LIKE','%' . $section->row  . '%')
                            ->where("lender_id", $lender_id)
                           ->where("loan_section_id", $section->section)
                           ->where("loan_sub_section_id", $section->subSection)
                           
                          ->where('ltv_from', '<=', $ltv)
                          ->where('ltv_to', '>=', $ltv)
                        //   ->where('value', '!=', -999)
                          ->where(function($q) use($loan_category){
                              return $q->where('loan_category', $loan_category)
                              ->orWhere('loan_category', LoanCategory::Any);
                          })
                            ->first();
                            if($cs != NULL){
                                $scores [] = $cs;
                            }
                            // echo "Cs is below ltv ". $ltv . " cs " . $creditScore . " section " . $section->section . " sub " . $section->subSection . "Cate ". $loan_category;
                            // echo json_encode($cs);
                            // die();
        }
        else if($section->subSection == LoanSubSection::LoanAmountType){
          $query = CreditScore::where("cs_from", '<=',$creditScore)
                                ->where("cs_to", '>=',$creditScore)
                                    ->where("lender_id", $lender_id)
                                    ->where("loan_section_id", $section->section)
                                    ->where("loan_sub_section_id", $section->subSection)
                                    // ->where("detail", 'LIKE','%' . $section->row  . '%')
                                    ->where("loan_to", '>=', $loan_amount )
                                    ->where("loan_from", '<=', $loan_amount)
                                    ->where(function($q) use($loan_category){
                                          $q->where('loan_category', $loan_category)
                                          ->orWhere('loan_category', LoanCategory::Any);
                                    });
                                    $cscores = $query->get();
                            if($cscores != NULL){
                              foreach($cscores as $cs){
                                if($cs != NULL){
                                  $scores [] = $cs;
                                }
                              }
                            }
        }
        // else if($section->subSection == LoanSubSection::Purchase){
        //   $query = CreditScore::where("cs_from", '<=',$creditScore)
        //                         ->where("cs_to", '>=',$creditScore)
        //                             ->where("lender_id", $lender_id)
        //                             ->where("loan_section_id", $section->section)
        //                             ->where("loan_sub_section_id", $section->subSection)
        //                             ->where("detail", 'LIKE','%' . $section->row  . '%')
        //                             ->where("loan_to", '>=', $loan_amount )
        //                             ->where("loan_from", '<=', $loan_amount)
        //                             ->where(function($q) use($loan_category){
        //                                   $q->where('loan_category', $loan_category)
        //                                   ->orWhere('loan_category', LoanCategory::Any);
        //                             });
        //                             $cscores = $query->get();
        //                     if($cscores != NULL){
        //                       foreach($cscores as $cs){
        //                         if($cs != NULL){
        //                           $scores [] = $cs;
        //                         }
        //                       }
        //                     }
        // }
        else if($section->subSection == LoanSubSection::YearRangeType){
          $query = CreditScore::where("cs_from", '<=',$creditScore)
                                ->where("cs_to", '>=',$creditScore)
                                    ->where("lender_id", $lender_id)
                                    ->where("loan_section_id", $section->section)
                                    ->where("loan_sub_section_id", $section->subSection)
                                    ->where("detail", 'LIKE','%' . $section->row  . '%')
                                    ->where("loan_to", '>=', $loan_amount )
                                    ->where("loan_from", '<=', $loan_amount)
                                    ->where("year_to", '>=', $year )
                                    ->where("year_from", '<=', $year)
                                    ->where(function($q) use($loan_category){
                                          $q->where('loan_category', $loan_category)
                                          ->orWhere('loan_category', LoanCategory::Any);
                                    });
                                    $cs = $query->first();
                            if($cs != NULL){
                                $scores [] = $cs;
                            }
        }
        else{
            $query = CreditScore::
                              where("cs_from", '<=',$creditScore)
                             ->where("cs_to", '>=',$creditScore)
                            ->where('ltv_from', '<=', $ltv)
                          ->where('ltv_to', '>=', $ltv)
                            ->where("lender_id", $lender_id)
                          ->where("loan_section_id", $section->section)
                          ->where("loan_sub_section_id", $section->subSection)
                          ->where("loan_to", '>=', $loan_amount )
                        ->where("loan_from", '<=', $loan_amount)
                          ->where(function($q) use($loan_category){
                              return $q->where('loan_category', $loan_category)
                              ->orWhere('loan_category', LoanCategory::Any);
                          })
                          ;
                           
                            // if($section->row == LoanSubSectionRow::RocketProLoanLimit){
                            //     $query = CreditScore::where("cs_from", '<=',$creditScore)
                            //     ->where("cs_to", '>=',$creditScore)
                            //         ->where("lender_id", $lender_id)
                            //         ->where("loan_section_id", $section->section)
                            //         ->where("loan_sub_section_id", $section->subSection)
                            //         ->where("detail", 'LIKE','%' . $section->row  . '%')
                            //         ->where("loan_to", '>=', $loan_amount )
                            //         ->where("loan_from", '<=', $loan_amount)
                            //         ->where(function($q) use($loan_category){
                            //               $q->where('loan_category', $loan_category)
                            //               ->orWhere('loan_category', LoanCategory::Any);
                            //         });
                            // }
                            //->where("detail", 'LIKE','%' . $section->row  . '%')
                           
                            // $cs = $query->first();
                            // if($cs != NULL){
                            //     $scores [] = $cs;
                            // }
                            
                            
                            $cscores = $query->get();
                            if($cscores != NULL){
                              foreach($cscores as $cs){
                                if($cs != NULL){
                                  $scores [] = $cs;
                                }
                              }
                            }
        }
    }
    // $scores["ltv"] = $ltv;
    // $scores["cs"] = $creditScore;
    // $scores["section"] = $section->section;
    // $scores["sub section"] = $section->subSection;
    // echo json_encode(["adons" => $scores]);
    // die();
      return $scores;

     // if($section->subSection == LoanSubSection::Others ){
         

//      }

   // return CreditScore::where("cs_from",$creditScore)->where("ltv_to", $ltv )->where("lender_id", $lender_id)->first();

   }

  function  calculateMonthlyPayment($loan_amount, $numberOfPayments , $monthlyPaymentsRate ){
    // echo "number " . $numberOfPayments;
    // echo "monthly " . $monthlyPaymentsRate;
    // die();
        return $loan_amount / (  ( ( pow(1+$monthlyPaymentsRate , $numberOfPayments ))-1 ) /   ($monthlyPaymentsRate  * pow (1+$monthlyPaymentsRate ,$numberOfPayments))  );
    }

    function  calculateMonthlyPaymentString($loan_amount, $numberOfPayments , $monthlyPaymentsRate ){
        return $loan_amount . '/'. '(  ( ( pow(1+'. $monthlyPaymentsRate . ',' .$numberOfPayments .' ))-1 ) /   ('. $monthlyPaymentsRate .   '* pow (1+'. $monthlyPaymentsRate . ',' . $numberOfPayments . '))  )';
    }

    function getValueClosest($search, $arr, $loan_amount) {
       $closest = null;
       $closestIndex = -1;
       for($i = 0; $i < count($arr); $i++){
          $item = $arr[$i];
          if ($closest === null || abs($search - $closest->value) > abs($item->value - $search)) {
             $closest = $item;
             $closestIndex = $i;
          }
       }
       // echo $closest;
       // die();
      // for($i = $closestIndex - 1; $i >= 0; $i--){
      //     $item = $arr[$i];
      //     $costOrCredit = abs(($item->value * ( $loan_amount  ))/100);
      //     //check if the cost or credit is < 3000
      //     if($costOrCredit < 3000){
      //       $closest = $item;
      //     }
      // }
// echo $closest;
// die();
       // foreach ($arr as $item) {
       //    if ($closest === null || abs($search - $closest->value) > abs($item->value - $search)) {
       //       $closest = $item;
       //    }
       // }
       if($closest == NULL){
           return count($arr);
       }

        return $closest;
    }

    function getLtvToValue($ltv){

        if( $ltv <= 60 ){
            return 60;
        }else
        if( $ltv >= 60.01 && $ltv <= 70 ){
            return 70;
        }else
        if( $ltv >= 70.01 && $ltv <= 75 ){
            return 75;
        }else
        if( $ltv >= 75.01 && $ltv <= 80 ){
            return 80;
        }else
        if( $ltv >= 80.01 && $ltv <= 85 ){
            return 85;
        }else
        if( $ltv >= 85.01 && $ltv <= 90 ){
            return 90;
        }else
        if( $ltv >= 90.01 && $ltv <= 95 ){
            return 95;
        }else
        if( $ltv >= 95.01 && $ltv <= 97 ){
            return 97;
        }
        return 97;
    }
   //620-639 , 640-659, 660-679,  680-699 , 700-719, 720-739 , >=740
    function getCreditScoreFromValue($creditScore){


        if( $creditScore >= 620 && $creditScore <= 639 ){
            return 620;
        }else
        if( $creditScore >= 640 && $creditScore <= 659 ){
            return 640;
        }else
        if( $creditScore >= 660 && $creditScore <= 679 ){
            return 660;
        }else
        if( $creditScore >= 680 && $creditScore <= 699 ){
            return 680;
        }else
        if( $creditScore >= 700 && $creditScore <= 719 ){
            return 700;
        }else
        if( $creditScore >= 720 && $creditScore <= 739 ){
            return 720;
        }
        else
        if( $creditScore >= 740 && $creditScore <= 759 ){
            return 740;
        }
        else
        if( $creditScore >= 760 && $creditScore <= 779 ){
            return 760;
        }
        else
        if( $creditScore >= 780 && $creditScore <= 799 ){
            return 780;
        }
        else
        if( $creditScore >= 800 && $creditScore <= 819 ){
            return 800;
        }
        return 800;
    }

    function roundFloat($value){
        return (float)number_format((float)$value, 3, '.', '') ;
   }

    function calculateAmotizationSchedule($loan_amount,$rate,$loan_start_date) {
      
      try {
            Carbon::parse($loan_start_date);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            dd('invalid date format for loan_start_date, please follow Y-M-D format');
        }
        $tdate=Carbon::now();
        $start = Carbon::parse($loan_start_date);
        
        $end =  Carbon::parse($tdate);
    
        $months = $end->diffInMonths($start);
        $noOfPaymentsTillDate = $months;
        $noOfPayments = 360;
        $balance = $loan_amount;
        $totalInterest=0;
        $interestRate = ($rate/1200);
        $interestRateInc = $interestRate+1;
        $powerOfInterestRate = pow($interestRateInc,$noOfPayments);
        $denominator = $powerOfInterestRate-1;
        $numerator =  $interestRate*$powerOfInterestRate;
        $payment = ($numerator*$balance)/$denominator;
        $payment = round($payment,2);
        $date = date('Y-m-d', strtotime('+1 month'));
        $newDate = date('Y-m-d', strtotime('+'.$noOfPayments.' month'));
        $start = $month = strtotime($date);
        $end = strtotime($newDate);
        $i=0;
        while($month <= $end)
        {
            $newBalance = $balance*($interestRate-1);
            $interestToPay = $balance - abs($newBalance);
            $principalPayment = $payment - $interestToPay;
            $newTotalBalance = $balance - $principalPayment;
            if($newTotalBalance<0)
            {
              $newTotalBalance = 0;
            }
            $balance =  $newTotalBalance;
            $totalInterest = $totalInterest + $interestToPay;
            $test[$i]=[
              'date' => date('F Y', $month),
              'payment' =>  round($payment,2),
              'principal_payment' => round($principalPayment,2),
              'interest_to_pay' => round($interestToPay,2),
              'total_interest' => round($totalInterest,2),
              'balance' => round($balance,2)
            ];
            $month = strtotime("+1 month", $month);
            $i++;
        }
       
        $data['number_of_payments_till_date'] = $noOfPaymentsTillDate;
        if($noOfPaymentsTillDate==0)
        {
          $data['interest_paid_till_date'] = 0;
          
        }
        else
        {
          $data['interest_paid_till_date'] = $test[$noOfPaymentsTillDate-1]['total_interest'];
          
         
        }
        return $data;
    }
  }
