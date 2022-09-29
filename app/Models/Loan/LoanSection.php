<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Model;

class LoanSection extends Model
{
    //
    const ConformingFixed = 1;
    const HighBalanceFixed = 2;
    const JMACJumboPlus = 3;
    const JmacManhattanJumbo = 4;
    const JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE = 5; // New Scenario
    const JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE = 6; // Existing Loans
    const JMACJumboSmart = 7;


    static function valueOf($section){

         switch ($section) {
            case 1:
                return "Conforming Fixed";
                break;
            case 2:
                return "High Balance Fixed";
                break;
            case 3:
                return "JMAC Jumbo Plus";
                break;
            case 4:
                return "JMAC MANHATTAN JUMBO";
                break;
            case 5:
                return "JMAC_LAGUNA_JUMBO_FIXED_ARMS_PURCHASE";
                break;
            case 6:
                return "JMAC_LAGUNA_JUMBO_FIXED_ARMS_REFINANCE";
                break;
            case 7:
                return "Jumbo Smart";
                break;
            
            default:
                return "";
                break;
         }
    }
}
