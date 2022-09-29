<?php

namespace App\Http\Resources\Loan;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Loan\LoanOption;
use App\Models\Loan\Loan;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            'low_rate' =>  $this->getData($this),
            'optimal_loan' =>  $this->getData($this->getLoan($this->id, LoanOption::Optimal)),//$this->getData($this->optimal)  ,
            'low_cost' => $this->getData($this->getLoan($this->id, LoanOption::LowCost)),
            'existing_loan' => $this->getData($this->getLoan($this->id, LoanOption::Existing)),
            // 'low_cost' =>  $this->getData($this->optimal)  ,
            'lender' => $this->lender,
            "categories_id" => $this->categories_id     
         ];
    }
    

    function getLoan($id, $loan_option){
        $loan = Loan::where('loan_parent_id', $id)->where(function ($q) use($loan_option) {
                                    $q->where('loan_option_id', $loan_option);
                    })
                ->first();
                return $loan;
    }

    function getData($data){
        if($data == null) { return null;}
        return [
             'required_monthly_payment' =>  (float)$data->monthly_payment,
             'rate' => (float)$data->rate,
             'total_amount_to_pay' => (float)$data->total_amount_to_pay,
             'down_payment' => (float)$data->down_payment  ,
             'credit_score' => (int)$data->credit_score,
             'zip_code' => $data->zip_code, 
             'created_at' => $data->created_at, 
             'user_type_id' => $data->use_type_id,
             'loan_amount' => (float)$data->loan_amount,
             'property_value' => (float)$data->property_value,
             'categories_id' => $data->categories_id  ,
             'proprety_type_id' => $data->proprety_type_id  ,
             'private_mortgage_insurance' => $data->private_mortgage_insurance,
             'number_of_payments_till_date' => $data->number_of_payments_till_date,
             'interest_paid_till_date' => $data->interest_paid_till_date,
             'Interest_left_to_pay' => $data->Interest_left_to_pay,
             'total_interest_to_pay' => $data->total_interest_to_pay,
             'cost_or_credit' => $data->cost_or_credit,
             'original_loan_amount' => $data->original_loan_amount,
         ];
    }
}


