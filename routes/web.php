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
Route::get('/', [UserController::class, 'redirectUser'])->middleware(['auth'])->name('home');
Route::get('/int', function () {return view('intern.home');})->middleware(['auth']);
Route::get('/ext', function () {return view('customer.home');})->middleware(['auth']);
//Files
  //incomes
Route::post('/upload_pakinglist/','UploadFileController@uploadPakinglist')->middleware(['auth','allow.only:user']);
Route::get('/download_pakinglist/{entrada}','UploadFileController@downloadPacking')->middleware('auth');
Route::post('/delete_pakinglist/','UploadFileController@deletePacking')->middleware(['auth','allow.only:user']);
Route::post('/upload_img_entrada/','UploadFileController@uploadImgEntrada')->middleware(['auth','allow.only:user']);
Route::post('/delete_img_entrada/','UploadFileController@deleteImgEntrada')->middleware(['auth','allow.only:user']);
Route::get('/upload_adj_entrada/','UploadFileController@uploadAdjEntrada')->middleware(['auth','allow.only:user']);

  //outcomes
Route::post('upload_pakinglist_outcome/','UploadFileController@uploadPakinglistOutcome')->middleware(['auth','allow.only:user']);
Route::post('/delete_pakinglist_outcome/','UploadFileController@deletePackingOutcome')->middleware(['auth','allow.only:user']);
Route::get('/download_pakinglist_outcome/{salida}/{filename}','UploadFileController@downloadPackingOutcome')->middleware('auth');
Route::post('/upload_img_salida/','UploadFileController@uploadImgSalida')->middleware(['auth','allow.only:user']);
Route::post('/delete_img_salida/','UploadFileController@deleteImgOutcome')->middleware(['auth','allow.only:user']);

//Route::resource('/ext/clientes', 'CustomerController')->middleware('auth');

//Incomes internal
Route::resource('/int/entradas', 'IncomeController')->middleware(['auth','allow.only:user']);
Route::get('/int/entradas_can_change_customer/{income}','IncomeController@can_change_customer')->middleware(['auth','allow.only:user']);

Route::get('/int/entradas_get_sums/{income}','IncomeController@get_income_sums')->middleware(['auth','allow.only:user']);

Route::get('/int/entradas/{income}/download_pdf','IncomeController@downloadPDF')->middleware(['auth','allow.only:user']);
Route::get('/int/entradas/{income}/delete','IncomeController@delete')->middleware(['auth','allow.only:user','add.bitacora:Eliminar Entrada']);
Route::get('/int/entradas_xls','IncomeController@download_incomes_xls')->middleware(['auth','allow.only:user']);
Route::get('/int/entradas/{income}/hide','IncomeController@hide')->middleware(['auth','allow.only:user','add.bitacora:Ocultar Entrada']);
Route::get('/int/entradas/{income}/unhide','IncomeController@unhide')->middleware(['auth','allow.only:user','add.bitacora:Revelar Entrada']);
//Incomes pre-entrada
Route::get('/int/preentrada/create','IncomeController@nueva_pre_entrada')->middleware(['auth','allow.only:user']);
Route::post('/int/preentrada/imprimir','IncomeController@imprimir')->middleware(['auth','allow.only:user']);
Route::get('/int/preentrada_etiqueta/{incomeid}','IncomeController@imprimir_etiquetas')->middleware(['auth','allow.only:user']);




//Income rows internal
Route::resource('/income_row', 'IncomeRowController')->middleware('auth')->middleware(['auth','allow.only:user']);
Route::get('/income_row_del/{income_row}', 'IncomeRowController@delete')->middleware(['auth','allow.only:user']);
Route::get('/income_row_has_outcomes/{income_row}', 'IncomeRowController@hasOutcomes')->middleware(['auth','allow.only:user']);
Route::get('/income_row_massive/{income_number}', 'IncomeRowController@masiva')->middleware(['auth','allow.only:user']);
Route::post('/income_row_massive_load', 'IncomeRowController@upload_masiva')->middleware(['auth','allow.only:user']);
Route::get('/download_massive_template','IncomeRowController@download_massive_template');
//Route::post('/income_row_massive_store_row', 'IncomeRowController@store_massive_row')->middleware(['auth','allow.only:user']);
Route::get('/income_row_massive_store_all/{income}', 'IncomeRowController@store_massive_rows')->middleware(['auth','allow.only:user']);
Route::post('/income_row_massive_clear_rows/{income}', 'IncomeRowController@clear_income_rows')->middleware(['auth','allow.only:user']);
//Part Numbers
Route::resource('/part_number', 'PartNumberController')->middleware(['auth','allow.only:user']);
//Route::get('/part_number/{partNumber}/{customer}/get','PartNumberController@getInfo')->where('partNumber', '[\w\s\-_\/]+')->middleware(['auth','allow.only:user']);
Route::post('/part_number/info/{customer}/get','PartNumberController@getInfo')->middleware(['auth','allow.only:user']);
//Route::get('/part_number/{partNumber}/{customer}/{numEntrada}/edit','PartNumberController@edit')->middleware(['auth','allow.only:user']);
Route::post('/part_number/info/{customer}/{numEntrada}/edit','PartNumberController@edit')->middleware(['auth','allow.only:user']);
Route::get('/part_number/{partNumber_id}/edit_existing','PartNumberController@edit_existing')->middleware(['auth','allow.only:user']);
Route::get('/part_number/{partNumber_id}/{numEntrada}/edit_existing','PartNumberController@edit_existing_update_mode')->middleware(['auth','allow.only:user']);
//Outcomes internal
Route::resource('/int/salidas', 'OutcomeController')->middleware('auth')->middleware(['auth','allow.only:user']);
Route::get('/int/salidas/{outcome}/delete','OutcomeController@delete')->middleware(['auth','allow.only:user','add.bitacora:Eliminar Salida']);
Route::get('/int/salidas_xls','OutcomeController@download_outcomes_xls')->middleware(['auth','allow.only:user']);
Route::get('/int/salidas_can_change_customer/{outcome}','OutcomeController@can_change_customer')->middleware(['auth','allow.only:user']);
Route::get('/int/salidas/{outcome}/download_pdf','OutcomeController@downloadPDF')->middleware(['auth','allow.only:user']);
Route::get('/int/salidas/{outcome}/hide','OutcomeController@hide')->middleware(['auth','allow.only:user','add.bitacora:Ocultar Salida']);
Route::get('/int/salidas/{outcome}/unhide','OutcomeController@unhide')->middleware(['auth','allow.only:user','add.bitacora:Revelar Salida']);

//Outcome Rows internal
Route::resource('/outcome_row', 'OutcomeRowController')->middleware(['auth','allow.only:user']);
Route::get('/outcome_row_delete/{outcome_row_id}','OutcomeRowController@destroy')->middleware(['auth','allow.only:user']);
//INVENTORY
Route::get('/int/inventory/{customer_id}/{days_before}','InventoryController@get')->middleware(['auth','allow.only:user']);
Route::get('/int/inventory','InventoryController@index')->middleware(['auth','allow.only:user']);
Route::get('/int/inventory_xls','InventoryController@downloadInventory')->middleware(['auth','allow.only:user']);
Route::get('/int/inventory/{cliente}/{rango}/{others}/complete','InventoryController@getAll')->middleware(['auth','allow.only:user']);
Route::get('/bultos_inventario_edit/{income_row}/{cantidad}','InventoryBundleController@edit_cantidad_bultos')->middleware(['auth','allow.only:user']);



//                 Defaults->   0       /     30     / NO_FILTER       <- cuando llames esta ruta no dejes vacios los campos
//Customer
Route::resource('/int/catalog/customers', 'CustomerController')->middleware(['auth','allow.only:user']);
//Carriers
Route::get('/int/catalog/carriers_add/{carrier}','CarrierController@add')->middleware(['auth','allow.only:user']);
Route::get('/int/catalog/carriers','CarrierController@index')->middleware(['auth','allow.only:user']);
//Supplier
Route::get('/int/catalog/suppliers_add/{supplier}','SupplierController@add')->middleware(['auth','allow.only:user']);
Route::get('/int/catalog/suppliers','SupplierController@index')->middleware(['auth','allow.only:user']);

//ORDEN DE CARGA
Route::get('/int/ordenes_de_carga','LoadOrderController@index_intern')->middleware(['auth','allow.only:user']);
Route::get('/int/salidas_OC/{load_order}','OutcomeController@loadOC')->middleware(['auth','allow.only:user']);
Route::get('/int/salidas_OC_load_rows/{load_order}','InventoryController@get_oc_partidas_html')->middleware(['auth','allow.only:user']);
Route::get('/int/salidas_OC_set_status/{load_order}/{outcome_number}','OutcomeController@set_oc_status')->middleware(['auth','allow.only:user']);

/////////////////////EXTARNAL FORM CUSTOMERS////////////////////////////
//INCOMES
Route::get('/ext/entradas', 'IncomeController@index_customer')->middleware(['auth','allow.only:customer']);
Route::get('/ext/entradas_xls','IncomeController@download_incomes_xls_customer')->middleware(['auth','allow.only:customer']);
Route::get('/quitar_on_hold/{income}','IncomeController@quitarOnHold')->middleware(['auth','allow.only:customer']);
Route::get('/ext/entradas/{income}/download_pdf','IncomeController@downloadPDFCustomer')->middleware(['auth','allow.only:customer']);
Route::get('/ext/salidas', 'OutcomeController@index_customer')->middleware(['auth','allow.only:customer']);
Route::get('/ext/salidas_xls','OutcomeController@download_outcomes_xls_customer')->middleware(['auth','allow.only:customer']);
Route::get('/ext/salidas/{outcome}/download_pdf','OutcomeController@downloadPDFCustomer')->middleware(['auth','allow.only:customer']);

//INVENTARIO
Route::get('/ext/inventario','InventoryController@index_customer')->middleware(['auth','allow.only:customer']);
Route::get('/ext/inventory_xls','InventoryController@downloadInventory_customer')->middleware(['auth','allow.only:customer']);
//ORDEN DE CARGA
Route::get('/ext/ordenes_de_carga','LoadOrderController@index')->middleware(['auth','allow.only:customer']);
Route::get('/ext/ordenes_de_carga/create','LoadOrderController@create')->middleware(['auth','allow.only:customer','permission.for:create_oc']);
Route::get('/ext/inventory_oc/{days_before}','InventoryController@get_for_oc')->middleware(['auth','allow.only:customer']);
Route::post('/ext/ordenes_de_carga','LoadOrderController@store')->middleware(['auth','allow.only:customer']);
Route::get('/ext/download_oc/{oc}','LoadOrderController@downloadOC')->middleware(['auth','allow.only:customer']);

//E-MAIL
Route::get('/sendemail/{numero_de_entrada}/entrada','EmailController@FormatoEmailEntrada');
Route::post('/sendemail_done/entrada','EmailController@sendEmailEntrada');

Route::get('/sendemail/{outcome}/salida','EmailController@FormatoEmailSalida');
Route::post('/sendemail_done/salida','EmailController@sendEmailSalida');

Route::get('/int/balance','IncomeController@getBalance')->middleware(['auth','allow.only:user']);
Route::get('/int/balance_pdf/{income}','IncomeController@getBalancePDF')->middleware(['auth','allow.only:user']);

Route::get('phpmyinfo', function () {
    phpinfo(); 
})->name('phpmyinfo');
require __DIR__.'/auth.php';
