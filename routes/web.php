<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\api\invoiceController;
use App\Http\Controllers\api\paymentRequestController;
use App\Http\Controllers\api\DocumentController;
use App\Http\Controllers\api\RequestCenterController;

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
Route::get('/migrate', function () {
    
    
    Artisan::call('migrate');
    dd(Artisan::output());
    
});
Route::get('/add-request', function () {
    return view('create-request');    
});
Route::post('/store-request',[RequestCenterController::class,'addRequestCenter']);

Route::get('/storagelink', function () {
    
    
    Artisan::call('storage:link');
    dd(Artisan::output());
    
});

Route::get('/storagelink/{folder}', function ($folder) {
    

    Artisan::call($folder);
    dd(Artisan::output());

});

Route::get('/print/{invoice_number}', [invoiceController::class, 'printInvoice']);

Route::get('/print_payments/{id}', [paymentRequestController::class, 'printPayment']);

Route::get('/document_management_export_pdf', [DocumentController::class, 'document_management_export_pdf']);


// Route::get('/info', function () {return view('info');});

Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');
 