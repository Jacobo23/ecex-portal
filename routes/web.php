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
//
Route::post('/upload_pakinglist/','UploadFileController@uploadPakinglist');
Route::get('int/entradas/{entrada}/download_packing/','UploadFileController@downloadPacking');
Route::post('/delete_pakinglist/','UploadFileController@deletePacking');
Route::post('/upload_img_entrada/','UploadFileController@uploadImgEntrada');
Route::post('/delete_img_entrada/','UploadFileController@deleteImgEntrada');
//
Route::resource('/int/entradas', 'IncomeController')->middleware('auth');
Route::resource('/ext/clientes', 'CustomerController')->middleware('auth');

require __DIR__.'/auth.php';
