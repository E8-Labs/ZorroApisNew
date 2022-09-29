<?php

namespace App\Models\Types;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    const Single_Family  	= 1;
    const Two_Unit 			= 2;
    const Three_Unit 		= 3;
    const Four_Unit 		= 4;
    const Condos 			= 5;
    const Townhomes 		= 6;
    const All               = 100;

}
