<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Model;
use App\Models\Loan\LoanSection;
use App\Models\Loan\LoanSubSection;


class LoanSubSectionRow extends Model
{
    //
    const AllARMSType = "All ARMS";
  const YearRangeType = "Year Range Type";
  const RocketProLoanLimit = "Loan Amount Range";
  const HomePointLoanLimitPrimaryOnly = "Loan Amount Range Primay Only";
  const RocketProInvestmentType = "Investment Property (DU & LP)";
  const HighBalanceAddOn = "DU/LP High Balance (All LTVs)";
  
  const SecondHomeLagunaJumbo = "Second Home Jumbo";
  const LagunaLoanAmountCondition = "Loan Amt >= 1 Million";
const CondoSimple = "Condo";
  const Purchase = "Purchase";
  const Occ_2ndHome = "Occ: 2nd Home";
    const Other = "Other";
    const Single_Family = "Single Family";
    const TwoToFourUnits = "2-4 Units"; // 2-4 Units
    const TwoUnits = "2 Unit";
    const ThreeToFourUnits = "3-4 Units";
    const Condo = "Att. Condo w/LTV > 75% & term > 15 Yr"; // 

    const NOO_LTV_EQ_LESS_75 = "NOO LTV <= 75%";

    const NOO_LTV_75_to_80 = "NOO LTV 75.01-80%";
    const NOO_LTV_EQ_GREATER_65 = "NOO & Cashout & LTV >=65%";


    const SECOND_HOME_LTV_EQ_LESS_75 = "Second Home LTV <= 85%";
    const SECOND_HOME_LTV_GREATER_75 = "Second Home LTV > 85%";
    
    //Rocket Pro Jumbo Sheet not using for now
    const SECOND_HOME_LTV_EQ_LESS_70 = "Second Home LTV <= 70%";
    const SECOND_HOME_LTV_GREATER_70 = "Second Home LTV > 70%";
    
    const Jumbo_30Y_Investment_Property = "Jumbo 30Y Investment Property";
    const RocketProRefinance = "R/T Refinance > 80";
    
    const JumboLoanAmountGreaterThan1Million = 1000000; // 1 million

  public $row;
  public $subSection;
  public $section;


  function __construct($section, $subSection, $row) {
      $this->row = $row;
      $this->subSection = $subSection;
      $this->section = $section;
  }

  function detail(){

        return "----------- " . LoanSection::valueOf($this->section ) . $this->section. " ----------- " . LoanSubSection::valueOf( $this->subSection) .$this->subSection . " -------- " . $this->row ;

  }


}