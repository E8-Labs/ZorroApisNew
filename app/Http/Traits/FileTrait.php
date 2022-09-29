<?php
namespace App\Http\Traits;


use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

trait FileTrait {


   public function saveImageFile(Request $request, $uploadedFileName,$foldePath, $oldNameToBeDelete){

           if($request->hasFile($uploadedFileName)){
                              $image = $request->file($uploadedFileName);
                              $random = rand(0,9999999);
                              $filenameWithExt = $image->getClientOriginalName();
                              $fileName = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                              $extension = $image->getClientOriginalExtension();
                              if($extension == null  || $extension == ""){ $extension = "jpg";  }
                              $fileNameToStrore  = $fileName. '_'.time() . $random .'.'.$extension;
                              $path = $image->storeAs( $foldePath , $fileNameToStrore );
                               if( $oldNameToBeDelete != ""  ) {
                                        Storage::delete( $foldePath . $oldNameToBeDelete);
                                }

                              return  $fileNameToStrore ;
          }
          return false;
    }



  }