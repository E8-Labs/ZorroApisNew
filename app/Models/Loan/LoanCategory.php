<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Model;

class LoanCategory extends Model
{
    //
    const Free_New = 1;
    const Free_Existing = 2;
    const Premium_New = 3;
    const Premium_Existing = 4;
    const Any = 5;

}
