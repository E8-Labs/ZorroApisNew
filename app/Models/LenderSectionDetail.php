<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LenderSectionDetail extends Model
{
    protected $fillable = [
        'loan_section_id',
        'rate',
        'years',
        '30days',
        'loan_balance_from',
        'loan_balance_to',
        'created_at',
        'updated_at'
    ];
}
