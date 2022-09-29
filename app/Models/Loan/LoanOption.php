<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Model;

class LoanOption extends Model
{
    //
    const LowRate = 1;
    const Optimal = 2;
    const LowCost = 3;
    const Existing = 4;
}
