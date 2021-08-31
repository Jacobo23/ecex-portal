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
Route::post('/upload_pakinglist/','UploadFileController@uploadPakinglist')->middleware(['auth','allow.only:user']);
Route::get('/download_pakinglist/{entrada}','UploadFileController@downloadPacking')->middleware('auth');
Route::post('/delete_pakinglist/','UploadFileController@deletePacking')->middleware(['auth','allow.only:user']);
Route::post('/upload_img_entrada/','UploadFileController@uploadImgEntrada')->middleware(['auth','allow.only:user']);
Route::post('/delete_img_entrada/','UploadFileController@deleteImgEntrada')->middleware(['auth','allow.only:user']);
  //outcomes
Route::post('upload_pakinglist_outcome/','UploadFileController@uploadPakinglistOutcome')->middleware(['auth','allow.only:user']);
Route::post('/delete_pakinglist_outcome/','UploadFileController@deletePackingOutcome')->middleware(['auth','allow.only:user']);
Route::get('/download_pakinglist_outcome/{salida}','UploadFileController@downloadPackingOutcome')->middleware('auth');
Route::post('/upload_img_salida/','UploadFileController@uploadImgSalida')->middleware(['auth','allow.only:user']);
Route::post('/delete_img_salida/','UploadFileController@deleteImgOutcome')->middleware(['auth','allow.only:user']);

//Route::resource('/ext/clientes', 'CustomerController')->middleware('auth');

//Incomes internal
Route::resource('/int/entradas', 'IncomeController')->middleware(['auth','allow.only:user']);
Route::get('/int/entradas/{income}/download_pdf','IncomeController@downloadPDF')->middleware('auth');
Route::get('/int/entradas/{income}/delete','IncomeController@delete')->middleware(['auth','allow.only:user']);
//Income rows internal
Route::resource('/income_row', 'IncomeRowController')->middleware('auth')->middleware(['auth','allow.only:user']);
Route::get('/income_row_has_outcomes/{income_row}', 'IncomeRowController@hasOutcomes')->middleware(['auth','allow.only:user']);
Route::get('/income_row_massive/{income_number}', 'IncomeRowController@masiva')->middleware(['auth','allow.only:user']);
Route::post('/income_row_massive_load', 'IncomeRowController@upload_masiva')->middleware(['auth','allow.only:user']);
Route::get('/download_massive_template','IncomeRowController@download_massive_template');
Route::post('/income_row_massive_store_row', 'IncomeRowController@store_massive_row')->middleware(['auth','allow.only:user']);
Route::post('/income_row_massive_clear_rows/{income}', 'IncomeRowController@clear_income_rows')->middleware(['auth','allow.only:user']);
//Part Numbers
Route::resource('/part_number', 'PartNumberController')->middleware(['auth','allow.only:user']);
Route::get('/part_number/{partNumber}/{customer}/get','PartNumberController@getInfo')->middleware(['auth','allow.only:user']);
Route::get('/part_number/{partNumber}/{customer}/{numEntrada}/edit','PartNumberController@edit')->middleware(['auth','allow.only:user']);
Route::get('/part_number/{partNumber_id}/edit_existing','PartNumberController@edit_existing')->middleware(['auth','allow.only:user']);
//Outcomes internal
Route::resource('/int/salidas', 'OutcomeController')->middleware('auth')->middleware(['auth','allow.only:user']);
Route::get('/int/salidas/{outcome}/delete','OutcomeController@delete')->middleware(['auth','allow.only:user']);
//Outcome Rows internal
Route::resource('/outcome_row', 'OutcomeRowController')->middleware(['auth','allow.only:user']);
Route::get('/outcome_row_delete/{outcome_row_id}','OutcomeRowController@destroy')->middleware(['auth','allow.only:user']);
//INVENTORY
Route::get('/int/inventory/{customer_id}/{days_before}','InventoryController@get')->middleware(['auth','allow.only:user']);
Route::get('/int/inventory','InventoryController@index')->middleware(['auth','allow.only:user']);
Route::get('/int/inventory_xls','InventoryController@downloadInventory')->middleware(['auth','allow.only:user']);
Route::get('/int/inventory/{cliente}/{rango}/{others}/complete','InventoryController@getAll')->middleware(['auth','allow.only:user']);
//                 Defaults->   0       /     30     / NO_FILTER       <- cuando llames esta ruta no dejes vacios los campos
//Customer
Route::resource('/int/catalog/customers', 'CustomerController')->middleware(['auth','allow.only:user']);
//Carriers
Route::get('/int/catalog/carriers_add/{carrier}','CarrierController@add')->middleware(['auth','allow.only:user']);
Route::get('/int/catalog/carriers','CarrierController@index')->middleware(['auth','allow.only:user']);
//Supplier
Route::get('/int/catalog/suppliers_add/{supplier}','SupplierController@add')->middleware(['auth','allow.only:user']);
Route::get('/int/catalog/suppliers','SupplierController@index')->middleware(['auth','allow.only:user']);

require __DIR__.'/auth.php';
