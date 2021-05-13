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
Route::get('/master-details', 'APIController@getMasterDetailsData')->name('api.master_details');
Route::get('/sales-perormance', 'APIController@getSalesPerformance')->name('api.sales_performance');
Route::get('/master-details/{id}', 'APIController@getMasterDetailsSingleData')->name('api.master_single_details');

// Report
Route::get('/report-details', 'APIController@getReportDetailsData')->name('api.report_details');
Route::get('/report-details/{id}', 'APIController@getReportDetailsSingleData')->name('api.report_single_details');

// Agency
Route::get('/agency-details/{id}', 'APIController@getAgencyDetailsSingleData')->name('api.agency_single_details');

Route::post('members', 'ClientController@storeMember');

Route::get('members', 'ClientsController@listMember');

Route::delete('members/{id}', 'ClientsController@deleteMember');

Route::get('sources', 'SourcesController@listSource');
Route::get('users', 'UsersController@listUser');
