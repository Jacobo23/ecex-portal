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
Route::get('/income_row_massive/{income_number}', 'IncomeRowController@masiva')->middleware('auth');
Route::post('/income_row_massive_load', 'IncomeRowController@upload_masiva')->middleware('auth');
Route::get('/download_massive_template','IncomeRowController@download_massive_template');
Route::post('/income_row_massive_store_row', 'IncomeRowController@store_massive_row')->middleware('auth');
Route::post('/income_row_massive_clear_rows/{income}', 'IncomeRowController@clear_income_rows')->middleware('auth');
//Part Numbers
Route::resource('/part_number', 'PartNumberController')->middleware('auth');
Route::get('/part_number/{partNumber}/{customer}/get','PartNumberController@getInfo');
Route::get('/part_number/{partNumber}/{customer}/{numEntrada}/edit','PartNumberController@edit');
Route::get('/part_number/{partNumber_id}/edit_existing','PartNumberController@edit_existing');
//Outcomes internal
Route::resource('/int/salidas', 'OutcomeController')->middleware('auth');
Route::get('/int/salidas/{outcome}/delete','OutcomeController@delete');
//Outcome Rows internal
Route::resource('/outcome_row', 'OutcomeRowController')->middleware('auth');
Route::get('/outcome_row_delete/{outcome_row_id}','OutcomeRowController@destroy');
//INVENTORY
Route::get('/int/inventory/{customer_id}/{days_before}','InventoryController@get');
Route::get('/int/inventory','InventoryController@index');
Route::get('/int/inventory_xls','InventoryController@downloadInventory');
Route::get('/int/inventory/{cliente}/{rango}/{others}/complete','InventoryController@getAll');
//                 Defaults->   0       /     30     / NO_FILTER       <- cuando llames esta ruta no dejes vacios los campos
//Customer
Route::resource('/int/catalog/customers', 'CustomerController')->middleware('auth');
//Carriers
Route::get('/int/catalog/carriers_add/{carrier}','CarrierController@add')->middleware('auth');
Route::get('/int/catalog/carriers','CarrierController@index')->middleware('auth');
//Supplier
Route::get('/int/catalog/suppliers_add/{supplier}','SupplierController@add')->middleware('auth');
Route::get('/int/catalog/suppliers','SupplierController@index')->middleware('auth');
//Route::get('/int/catalog/carriers/get','CarrierController@index_obj')->middleware('auth');

require __DIR__.'/auth.php';
