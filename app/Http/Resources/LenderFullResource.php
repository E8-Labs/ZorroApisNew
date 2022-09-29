<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Loan\Loan;
use App\Models\Loan\LoanCategory;
use App\Models\Lender;

class LenderFullResource extends JsonResource
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
            'id' => $this->id,
             'name' =>  $this->name,
             'count' =>  Loan::where('lender_id', $this->id )->count(),
             'logo_url' => $this->logo_url,
             'website' => $this->website,
             'address' => $this->address ,// (int)$this->subscription_id,
             "phone" => $this->phone,
        "detail" => $this->detail
         ];
    }
}
