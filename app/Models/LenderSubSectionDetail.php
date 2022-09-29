<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LenderSubSectionDetail extends Model
{
    protected $fillable = [
        'sub_section_id',
        'category',
        'parent_id',
        'sub_category',
        'type',
        '620-639',
        '640-659',
        '660-679',
        '680-699',
        '700-719',
        '720-739',
        '>= 740',
        'loan_to',
        'loan_from',
        'description',
        'created_at',
        'updated_at'
    ];
}
