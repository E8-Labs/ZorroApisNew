<?php

namespace App\Http\Controllers\Admin;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lender;
use App\Models\Rate\Rate;
use App\Models\CreditScore;
use App\Models\Loan\LoanSubSection ;
use App\Models\Loan\LoanSection ;
use App\Models\County;
use App\Models\LenderSubSectionDetail;
use App\Models\LenderSectionDetail;

class LenderController extends Controller
{
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $lenders = Lender::all();
        return view('admin.lenders.index', compact("lenders"));
    }

    public function details($id)
    {
        $lender = Lender::findOrFail($id);
        $rates = Rate::where("lender_id",$lender->id)->get();
        $creditScores = CreditScore::where("lender_id",$lender->id)->get();
        return view('admin.lenders.details', compact("lender", "rates", "creditScores"));
    }


    public function updateCreditScoreCVS(Request $request)
    {
        $lenderId = $request["lender_id"];
        $csv_data = $this->readCSV($request, 'csv_file');

        if($this->isValidCreditScoreCSV($csv_data)){
           try{
             \DB::beginTransaction();
              CreditScore::where('lender_id',$lenderId)->delete();
           $output = "";
           foreach ($csv_data as $key =>$row) {
               if ($key != 0 ) { // skip titles
                    $output .=  $row[0] . ' ===  ';
                    foreach ($row as $innerKey => $innerRow) {
                        if ($innerKey != 0 ) {
                           // $output .= $innerRow . ' (' . $this->getFromValue( $csv_data[0][$innerKey]) . ',' . $this->getToValue( $csv_data[0][$innerKey]). ")  ";
                          //  $output .= ' [' . $this->getFromValue( $row[0]) . ',' . $this->getToValue( $row[0]). "]  ";
                            $cs = new CreditScore;
                            $cs->lender_id = $lenderId;
                            $cs->cs_from = $this->getFromValue( $csv_data[0][$innerKey]);
                            $cs->cs_to = $this->getToValue( $csv_data[0][$innerKey]);

                            $cs->ltv_from = $this->getFromValue( $row[0]);
                            $cs->ltv_to = $this->getToValue( $row[0]);
                            $cs->value = (float)$innerRow ;

                            $cs->detail = $row[0];

                            $cs->save();
                         }
                     }
                    $output .= "<br> ";
                 }
           }
              \DB::commit();
              return response()->json(['message' => "Successfully added",'success' => true], 200) ;
            } catch (\Exception $e) {
                      \DB::rollback();
                     return response()->json(['message' => "Something went wrong : " . $e ,'success' => false], 200) ;
         }
        }else {
            return "Invalid CSV file";
        }
    }


    public function updateCreditScorePropertyTypeCVS(Request $request)
    {
        $lenderId = $request["lender_id"];
        $csv_data = $this->readCSV($request, 'csv_file');

        if($this->isValidCreditScoreCSV($csv_data)){
           try{
             \DB::beginTransaction();
             // CreditScore::where('lender_id',$lenderId)->delete();
           $output = "";
           foreach ($csv_data as $key =>$row) {
               if ($key != 0 ) { // skip titles
                    $output .=  $row[0] . ' ===  ';
                    foreach ($row as $innerKey => $innerRow) {
                        if ($innerKey != 0 ) {
                      //   $output .= $innerRow . ' (' . $this->getFromValue( $csv_data[0][$innerKey]) . ',' . $this->getToValue( $csv_data[0][$innerKey]). ")  ";
                           // $output .= ' [' . $this->getFromValue( $row[0]) . ',' . $this->getToValue( $row[0]). "]  ";


                         // $output .= $innerRow . ' (' . $this->getFromValue( $csv_data[0][$innerKey]) . ',' . $this->getToValue( $csv_data[0][$innerKey]). ")  ";

                           $output .=   ' --- '  .  (float)$innerRow;

                            $cs = new CreditScore;
                            $cs->lender_id = $lenderId;
                           // $cs->cs_from = $this->getFromValue( $csv_data[0][$innerKey]);
                           // $cs->cs_to = $this->getToValue( $csv_data[0][$innerKey]);

                           // $cs->ltv_from = $this->getFromValue( $row[0]);
                           // $cs->ltv_to = $this->getToValue( $row[0]);
                            $cs->value = (float)$innerRow ;

                            $cs->detail = $row[0];
                            $cs->loan_section_id = LoanSection::ConformingFixed;
                            $cs->loan_sub_section_id = LoanSubSection::PropertyType;

                            $cs->save();
                         }
                     }
                    $output .= "<br> ";
                 }
           }
              \DB::commit();
           return $output;
              return response()->json(['message' => "Successfully added",'success' => true], 200) ;
            } catch (\Exception $e) {
                      \DB::rollback();
                     return response()->json(['message' => "Something went wrong : " . $e ,'success' => false], 200) ;
         }
        }else {
            return "Invalid CSV file";
        }
    }

    public function updateCreditScoreOccupancyCVS(Request $request)
    {
        $lenderId = $request["lender_id"];
        $csv_data = $this->readCSV($request, 'csv_file');

        if($this->isValidCreditScoreCSV($csv_data)){
           try{
             \DB::beginTransaction();
             // CreditScore::where('lender_id',$lenderId)->delete();
           $output = "";
           foreach ($csv_data as $key =>$row) {
               if ($key != 0 ) { // skip titles
                    $output .=  $row[0] . ' ===  ';
                    foreach ($row as $innerKey => $innerRow) {
                        if ($innerKey != 0 ) {
                      //   $output .= $innerRow . ' (' . $this->getFromValue( $csv_data[0][$innerKey]) . ',' . $this->getToValue( $csv_data[0][$innerKey]). ")  ";
                           // $output .= ' [' . $this->getFromValue( $row[0]) . ',' . $this->getToValue( $row[0]). "]  ";


                         // $output .= $innerRow . ' (' . $this->getFromValue( $csv_data[0][$innerKey]) . ',' . $this->getToValue( $csv_data[0][$innerKey]). ")  ";

                           $output .=   ' --- '  .  (float)$innerRow;

                            $cs = new CreditScore;
                            $cs->lender_id = $lenderId;
                           // $cs->cs_from = $this->getFromValue( $csv_data[0][$innerKey]);
                           // $cs->cs_to = $this->getToValue( $csv_data[0][$innerKey]);

                           // $cs->ltv_from = $this->getFromValue( $row[0]);
                           // $cs->ltv_to = $this->getToValue( $row[0]);
                            $cs->value = (float)$innerRow ;

                            $cs->detail = $row[0];
                            $cs->loan_section_id = LoanSection::ConformingFixed;
                            $cs->loan_sub_section_id = LoanSubSection::Occupancy;

                            $cs->save();
                         }
                     }
                    $output .= "<br> ";
                 }
           }
              \DB::commit();
           return $output;
              return response()->json(['message' => "Successfully added",'success' => true], 200) ;
            } catch (\Exception $e) {
                      \DB::rollback();
                     return response()->json(['message' => "Something went wrong : " . $e ,'success' => false], 200) ;
         }
        }else {
            return "Invalid CSV file";
        }
    }

    function getFromValue($value){

        if (strpos($value, '<=') !== false) {
            return 0;
        }

        if (strpos($value, '>=') !== false) {
            return (float)explode(">=",$value)[1];
        }
        return (float)explode("-",$value)[0];
    }
    function getToValue($value){

        if (strpos($value, '<=') !== false) {
            return (float)explode("<=",$value)[1];
        }

        if (strpos($value, '>=') !== false) {
            return -1;
        }

        return (float)explode("-",$value)[1];
    }

    function isValidCreditScoreCSV($csv_data){
        $ltv = trim($csv_data[0][0]," ");
        //$ThirtyDay = trim($csv_data[0][1]," ");
        if( strcasecmp( $ltv, "ltv") == 0 ){
            return true;
       }
      return false;
    }


    public function updateRateCVS(Request $request)
    {
        $lenderId = $request["lender_id"];
        $csv_data = $this->readCSV($request, 'csv_file');
        $rateIndex = 0;
        $thirtyDayIndex = 1;
        if($this->isValidRateCSV($csv_data)){
            try{
             \DB::beginTransaction();
             Rate::where('lender_id',$lenderId)->delete();
             $output = "";
           foreach ($csv_data as $key =>$row) {
                if ($key != 0 ) { // skip titles
                    // $output = $output . (float)$row[$rateIndex] . '   ' . (float)$row[$thirtyDayIndex]; 
                    // $output .= "<br> ";
                    $rate = new Rate;
                    $rate->lender_id = $lenderId;
                    $rate->rate = (float)$row[$rateIndex];
                    $rate->value = (float)$row[$thirtyDayIndex];
                    $rate->value = (float)$row[$thirtyDayIndex];
                    $rate->year_type_id = 1; //30 YR
                    $rate->day_type_id = 3; // 30 day
                    $rate->save();
            }
           }
             \DB::commit();
              return response()->json(['message' => "Successfully added",'success' => true], 200) ;
            } catch (\Exception $e) {
                      \DB::rollback();
                     return response()->json(['message' => "Something went wrong : " . $e ,'success' => false], 200) ;
         }
        }else {
            return "Invalid CSV file";
        }
    }
    function isValidRateCSV($csv_data){
        $rate = trim($csv_data[0][0]," ");
        $ThirtyDay = trim($csv_data[0][1]," ");
        if( strcasecmp( $rate, "Rate") == 0  && strcasecmp($ThirtyDay, "30 Day") == 0  ){
            return true;
        }
      return false;
    }

   function readCSV(Request $request,$file_name ){
      ini_set('auto_detect_line_endings',TRUE);
      $path = $request->file($file_name)->getRealPath();
      $csv_data = array_map('str_getcsv', file($path));
      return $csv_data;
    }

    function import(){
        return view('import');
    }

    public function downloadFile()
    {
        $myFile = public_path("dummy.pdf");
        $headers = ['Content-Type: application/pdf'];
        $newName = 'nicesnippets-pdf-file-'.time().'.pdf';

        return response()->download($myFile, $newName, $headers);
    }   


    function lender_sections_details_TypeCVS(Request $request) {
        $csv_data = $this->csvToArray($request['csv_file']);
        //$rateIndex = 0;
       // $thirtyDayIndex = 1;
        
       
        foreach ($csv_data as $key =>$row) {
            // dd($row);  
                $values = LenderSectionDetail::updateOrCreate(
                    [
                        'loan_section_id' =>  1 ?? null,
                        'rate'=> $row['rate']?? null, 
                        '30days' => $row['30days'] ?? null,
                        'years' => $row['years']?? null ,
                        'loan_balance_from' => $row['loan_balance_from'] ?? null,
                        'loan_balance_to'  => $row['loan_balance_to'] ?? null 
                    ],
                    [
                        'loan_section_id' =>  1 ?? null , 
                        'rate'=> $row['rate']?? null, 
                        '30days' => $row['30days'] ?? null,
                        'years' => $row['years']?? null ,
                        'loan_balance_from' => $row['loan_balance_from'] ?? null,
                        'loan_balance_to'  => $row['loan_balance_to'] ?? null 
                    ]
                );                   
        }
        return back();
    }
    function lender_sub_sections_details_TypeCVS(Request $request) {
        $csv_data = $this->csvToArray($request['csv_file']);
        $rateIndex = 0;
        $thirtyDayIndex = 1;
        foreach ($csv_data as $key =>$row) {

            $values = LenderSubSectionDetail::updateOrCreate(
                    [ 
                        'sub_section_id' => $row['sub_section_id'] ?? null,
                        'category' => $row['category'] ?? null,
                        'sub_category'  => $row['sub_category'] ?? null ,
                        'type' => "" ?? null ,
                        '620-639' =>  $row['620-639'] ?? null ,
                        '640-659'  => $row['640-659'] ?? null,
                        '660-679'  => $row['660-679'] ?? null,
                        '680-699'  => $row['680-699'] ?? null,
                        '700-719'  => $row['700-719'] ?? null,
                        '720-739'  => $row['720-739'] ?? null,
                        '>= 740'  => $row['>= 740'] ?? null,
                        'loan_to'  => "" ?? null ,
                        'loan_from'  => "" ?? null ,
                        'description'  => "" ?? null 
                    ],
                    [ 
                        'sub_section_id' => $row['sub_section_id'] ?? null,
                        'category' => $row['category'] ?? null,
                        'sub_category'  => $row['sub_category'] ?? null ,
                        'type' => "" ?? null ,
                        '620-639' =>  $row['620-639'] ?? null ,
                        '640-659'  => $row['640-659'] ?? null,
                        '660-679'  => $row['660-679'] ?? null,
                        '680-699'  => $row['680-699'] ?? null,
                        '700-719'  => $row['700-719'] ?? null,
                        '720-739'  => $row['720-739'] ?? null,
                        '>= 740'  => $row['>= 740'] ?? null,
                        'loan_to'  => "" ?? null ,
                        'loan_from'  => "" ?? null ,
                        'description'  => "" ?? null 
                    ]
                );
            
        }   
        return back();
    }


    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle,5000,  $delimiter)) !== false)
            {
                if (!$header) {
                    $header = $row;
                }
                else
                {
                    $data[] = array_combine($header, $row);

                }
            }
            fclose($handle);
        }
        return $data;
    }

    function counties_TypeCVS(Request $request) {
        $csv_data = $this->csvToArray($request['csv_file']);
    // $rateIndex = 0;
    // $thirtyDayIndex = 1;
        foreach ($csv_data as $key =>$row) {
        
                $values = County::updateOrCreate(
                    [ 
                        'name'=> $row['name']
                    
                    ],
                    [ 
                        'name'=> $row['name'] ?? null,
                        'fips_state_code' => $row['fips_state_code'] ?? null,
                        'fips_county_code' => $row['fips_county_code'] ?? null,
                        'state'  => $row['state'] ?? null ,
                        'cbsa_number' =>  $row['cbsa_number'] ?? null ,
                        'one_unit_limit'  => $row['one_unit_limit'] ?? null,
                        'two_unit_limit'  => $row['two_unit_limit'] ?? null,
                        'three_unit_limit'  => $row['three_unit_limit'] ?? null,
                        'four_unit_limit'  => $row['four_unit_limit'] ?? null
                    ]
                );
        }
        return back();
    }
}
