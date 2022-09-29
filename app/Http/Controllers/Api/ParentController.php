<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator as MyValidator;

class ParentController extends Controller
{
    var $SUCCESS = "success";
    var $MSG = "message";
    var $DATA = "data";
    var $UPATED_SUCCESSFULLY = "Updated successfully";
    var $DELETED_SUCCESSFULLY = "Deleted successfully";

     public function getErrorResponse(MyValidator $validator){
      return response()->json([$this->MSG  => $validator->messages() ,$this->SUCCESS => false], 200) ;
    }
}
