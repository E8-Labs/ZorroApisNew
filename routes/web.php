<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/lenders', 'Admin\LenderController@index')->name('lenders');
Route::get('/lender-detail/{id}', 'Admin\LenderController@details')->name('lender-detail');
Route::post('/update-rate-csv', 'Admin\LenderController@updateRateCVS')->name('update-rate-csv');

// Route::post('/update-credit-score-csv', 'Admin\LenderController@updateCreditScoreCVS')->name('update-credit-score-csv');
// Route::post('/update-credit-score-csv', 'Admin\LenderController@updateCreditScorePropertyTypeCVS')->name('update-credit-score-csv');
Route::post('/update-credit-score-csv', 'Admin\LenderController@updateCreditScoreOccupancyCVS')->name('update-credit-score-csv');

Route::get('home/import', 'Admin\LenderController@import')->name('import');

Route::post('home/importLenderSections', 'Admin\LenderController@lender_sections_details_TypeCVS')->name('importLenderSections');
Route::post('home/importLendersubSections', 'Admin\LenderController@lender_sub_sections_details_TypeCVS')->name('importLendersubSections');
Route::post('home/importcounties', 'Admin\LenderController@counties_TypeCVS')->name('counties');
