<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Model;


class LoanSubSection extends Model
{
    //
    const PropertyType = 1;
    const Occupancy = 2;
    const Others = 3;
    const LoanAmountType = 4;
    const YearRangeType = 5;
    const AllARMSType = 6;


        static function valueOf($section){

         switch ($section) {
            case 1:
                return "Property Type";
                break;
            case 2:
                return "Occupancy";
                break;
            case 3:
                return "Others";
                break;

                case 4:
                return "Loan Amount Type";
                break;

                case 5:
                return "Year Range Type";
                break;
                
                case 6:
                return "All ARMS";
                break;
                
            default:
                return "";
                break;
         }
    }
}
