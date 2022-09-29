<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as MyValidator;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;




class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getErrorResponse(MyValidator $validator){
      return response()->json(['message' => $validator->messages() ,'success' => false], 200) ;
    }

    public function sendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email']);
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 
            return response()->json(['message' => "Verification Code sent successfully" ,'success' => true], 200) ;
          }
    }

    public function apiLogin(Request $request)
    {
        
        $validator = Validator::make($request->all(), ['email'=>'required|email','verification_code'=>'required|numeric',
            'platform'=>'required','device_id'=>'required' ]); //'device_token'=>'required', 
          if ($validator->fails()) {
            return $this->getErrorResponse($validator);
          }else{ 

            $is_new_user = false;
            $verificationCode =  $request['verification_code'];
            // return "Hello Code " . $verificationCode;
            $email =  $request['email'];
            $deviceId =  $request['device_id'];

            if($verificationCode === "1122"){

                $user = User::where( 'email', $email )->first();
                if ($user == null){
                  $is_new_user = true;

                    $user = User::where( 'email', $deviceId )->first();

                    if($user == null){ 
                        $user = new User;
                        $user->email = $email;
                        $user->password = Hash::make(\Config::get('constants.system_key'));
                        $user->save();
                    }else {
                        $user->email = $email;
                        $user->save();
                    }
                }

                $tokenRequest = $request->create('/oauth/token', 'POST', $request->all());
                $request->request->add([
                   "client_id"     => '2',
                   "client_secret" => 'M4xjVt51FssZGsQQZWfMOV9V5clCsQWUAdJzzJGS',
                   "grant_type"    => 'password',
                   "password"    => \Config::get('constants.system_key'),
                   "username" => $email
                ]);

                $response = \Route::dispatch($tokenRequest);
                $json = (array) json_decode($response->getContent());

                // return $response;

                 if (isset($json['access_token'])) {
                    //return response()->json(['message' => $json ,'success' => true], 200) ;
                 //   $json['success'] = true;
                 //   $json['message'] = "";
                    //$user = User::where( 'email',  $email )->first();
                    //$json['user']  = $user;

                  $json['profile']  = $user->getProfile();
                  $json['is_new_user']  = $is_new_user;

                  $response->setContent(json_encode($json));

                  

                  //  $json['user']  = new UserResource($user);
                    //  $plateform = "";
                    // if (isset($request["plateform"])) {
                    //    $plateform =   $request["plateform"];
                    // }
                    // $device_token = "";
                    // if (isset($request["device_token"])) {
                    //    $device_token =   $request["device_token"];
                    // }
                    // $device_id = "";
                    // if (isset($request["device_id"])) {
                    //    $device_id =   $request["device_id"];
                    // }
                    // $user->updateDeviceToken($device_token, $plateform, $device_id);
                   // return $response;   
                     return response()->json([ "data" => $json ,  'message' => "" ,'success' => true], 200) ;    
                }else{ // access_token
                        return response()->json(['message' => "Invalid Access Token" ,'success' => false, 'error_data' => $json], 200) ;
                }
                $response->setContent(json_encode($json));
                return $response;


                return $user;
                return response()->json(['message' => "Verified successfully" ,'success' => true], 200) ;  
          }else{
              return response()->json(['message' => "Invalid Code" . $verificationCode ,'success' => false], 200) ;
          }

            
          }
    }

}
