<?php

namespace App\Http\Resources\Loan;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanLiteResource extends JsonResource
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
            'lender' => $this->lender,
            "categories_id" => $this->categories_id
         ];
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
             'loan_amount' => (float)$data->loan_amount,
             'property_value' => (float)$data->property_value,
             'categories_id' => $data->categories_id,
             'section_id' => 1,
             'proprety_type_id' => $this->proprety_type_id
         ];
    }
}
