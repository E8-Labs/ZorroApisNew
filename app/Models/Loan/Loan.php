<?php

namespace App\Models\Loan;

use App\Models\Lender;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    //



    

    public function optimal()
    {
        return $this->hasOne(Loan::class, "id", "loan_parent_id");
    }

    public function lender()
    {
        return $this->hasOne(Lender::class, "id", "lender_id")->select(['id','name','logo_url','website']);
    }

  static function saveLoan($user_id,$categories_id, $lender_id, $monthly_payment,$rate, $total_amount_to_pay, $down_payment,
                 $credit_score,$loan_amount, $zip_code,
                 $proprety_type_id,$loan_type_id, $use_type_id , 
                 $loan_option_id, $loan_parent_id, $property_value, $private_mortgage_insurance = 0, $number_of_payments_till_date = 0, $interest_paid_till_date = 0, $Interest_left_to_pay = 0, $total_interest_to_pay = 0, $cost_or_credit = 0, $original_loan_amount = 0 ){
      $loan = new Loan;
       $loan->user_id = $user_id;
       $loan->categories_id = $categories_id;
       $loan->lender_id = $lender_id;
       $loan->loan_type_id = $loan_type_id;
       $loan->proprety_type_id = $proprety_type_id;

       $loan->use_type_id = $use_type_id;
       $loan->monthly_payment = round($monthly_payment, 2);;
       $loan->rate = $rate;
       $loan->total_amount_to_pay = round($total_amount_to_pay, 2);
       $loan->down_payment = round($down_payment, 2);
       $loan->credit_score = $credit_score;

       $loan->loan_amount =  round($loan_amount, 2); 
       $loan->zip_code = $zip_code;
       $loan->original_loan_amount = $original_loan_amount;
       $loan->cost_or_credit = $cost_or_credit;
       
       $loan->private_mortgage_insurance = $private_mortgage_insurance;
       $loan->number_of_payments_till_date = $number_of_payments_till_date;
       $loan->interest_paid_till_date = $interest_paid_till_date;
       $loan->Interest_left_to_pay = $Interest_left_to_pay;
       $loan->total_interest_to_pay = $total_interest_to_pay;
       
       

       $loan->loan_option_id = $loan_option_id;
       $loan->loan_parent_id = $loan_parent_id;
       $loan->property_value = $property_value;
      $loan->save();
      return $loan; 
    }
}
