<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Auth
Route::post('/send_verification_code', 'Auth\LoginController@sendVerificationCode');
Route::post('/login', 'Auth\LoginController@apiLogin');
// Auth End

Route::post('/free_calculation', 'Api\CalculationController@freeCalculation');

Route::post('/free_existing_calculation', 'Api\CalculationController@freeExistingCalculation');


Route::post('/premium_calculation', 'Api\CalculationController@premiumCalculation')->middleware('auth:api');
Route::post('/amortization_schedule', 'Api\CalculationController@amortizationSchedule');
Route::post('/premium_existing_calculation', 'Api\CalculationController@premiumExistingCalculation')->middleware('auth:api');

// Common 
Route::get('/counties', 'Api\CommonController@counties');
Route::get('/types', 'Api\CommonController@types');
Route::get('/subscriptions', 'Api\CommonController@subscriptions');
Route::post('/loan_history', 'Api\CommonController@loanHistory')->middleware('auth:api');

// User 
Route::get('/user_profile', 'Api\UserController@profile')->middleware('auth:api');
Route::post('/update_user_profile', 'Api\UserController@updateProfile')->middleware('auth:api');


// Admin 
Route::post('/admin_dashboard', 'Api\AdminController@dashboard')->middleware('auth:api');
Route::post('/users', 'Api\AdminController@users')->middleware('auth:api');
Route::post('/user_profile_for_admin', 'Api\AdminController@userProfile')->middleware('auth:api');
Route::post('/user_loans', 'Api\AdminController@userLoans')->middleware('auth:api');
Route::post('/delete_user', 'Api\AdminController@deleteUser')->middleware('auth:api');
Route::post('/lenders_list', 'Api\AdminController@lendersList')->middleware('auth:api');
Route::post('/all_loans', 'Api\AdminController@allLoansList')->middleware('auth:api');
Route::post('/loan_detail', 'Api\AdminController@getLoanDetail')->middleware('auth:api');
//getLoanDetail


