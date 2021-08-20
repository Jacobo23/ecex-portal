<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//HOME
Route::get('/', [UserController::class, 'redirectUser'])->middleware(['auth']);
Route::get('/int', function () {return view('intern.home');})->middleware(['auth']);
Route::get('/ext', function () {return view('customer.home');})->middleware(['auth']);
//Files
  //incomes
Route::post('/upload_pakinglist/','UploadFileController@uploadPakinglist');
Route::get('/download_pakinglist/{entrada}','UploadFileController@downloadPacking');
Route::post('/delete_pakinglist/','UploadFileController@deletePacking');
Route::post('/upload_img_entrada/','UploadFileController@uploadImgEntrada');
Route::post('/delete_img_entrada/','UploadFileController@deleteImgEntrada');
  //outcomes
Route::post('upload_pakinglist_outcome/','UploadFileController@uploadPakinglistOutcome');
Route::post('/delete_pakinglist_outcome/','UploadFileController@deletePackingOutcome');
Route::get('/download_pakinglist_outcome/{salida}','UploadFileController@downloadPackingOutcome');
Route::post('/upload_img_salida/','UploadFileController@uploadImgSalida');
Route::post('/delete_img_salida/','UploadFileController@deleteImgOutcome');

//Route::resource('/ext/clientes', 'CustomerController')->middleware('auth');

//Incomes internal
Route::resource('/int/entradas', 'IncomeController')->middleware('auth');
Route::get('/int/entradas/{income}/download_pdf','IncomeController@downloadPDF');
Route::get('/int/entradas/{income}/delete','IncomeController@delete');
//Income rows internal
Route::resource('/income_row', 'IncomeRowController')->middleware('auth');
Route::get('/income_row_has_outcomes/{income_row}', 'IncomeRowController@hasOutcomes')->middleware('auth');
//Part Numbers
Route::resource('/part_number', 'PartNumberController')->middleware('auth');
Route::get('/part_number/{partNumber}/{customer}/get','PartNumberController@getInfo');
Route::get('/part_number/{partNumber}/{customer}/{numEntrada}/edit','PartNumberController@edit');
//Outcomes internal
Route::resource('/int/salidas', 'OutcomeController')->middleware('auth');
//Outcome Rows internal
Route::resource('/outcome_row', 'OutcomeRowController')->middleware('auth');
Route::get('/outcome_row_delete/{outcome_row_id}','OutcomeRowController@destroy');
//INVENTORY
Route::get('/int/inventory/{customer_id}/{days_before}','InventoryController@get');

require __DIR__.'/auth.php';
