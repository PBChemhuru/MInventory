<?php

use App\Http\Controllers\Admincontroller;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\clincStockController;
use App\Http\Controllers\DispenseController;
use App\Http\Controllers\StockTransactions;
use App\Http\Controllers\StockTransactionsController;
use App\Http\Controllers\distributeStock;
use App\Http\Controllers\distributeStockController;
use App\Http\Controllers\MailerController;
use App\Http\Controllers\mainStockController;
use App\Http\Controllers\printcontroller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\requestController;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Models\clinic_stock;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//show add form
Route::get('/StockTransactions', [StockTransactionsController::class, 'show'])->middleware('auth')->name('StockTransactions');
//store item
Route::post('/StockTransactions', [StockTransactionsController::class, 'store'])->middleware('auth')->name('storeStock');
//search main stock
Route::get('/mainstock/search', [mainStockController::class,  'searchmain'])->middleware('auth')->name('searchmainstock');
//show mainstock
Route::get('/mainstock', [mainStockController::class, 'showmain'])->middleware('auth')->name('mainstock');
//update to mainstock
Route::patch('/mainstock/{stock_item}', [mainStockController::class, 'updatemain'])->middleware('auth')->name('updateStock');
//delete mainstock
Route::delete('/mainstock/delete/{id}', [mainStockController::class, 'deletemain'])->name('mainstock.delete');
//Add new stock item
Route::post('/mainstock', [mainStockController::class, 'addnewitem'])->middleware('auth')->name('addnewitem');
//transaction journal search
Route::post('/StockTransactions/search', [StockTransactionsController::class, 'seachjournal'])->middleware('auth')->name('searchStock');
//printing report routes
Route::post('/StockTransactions/search/print', [printcontroller::class, 'printstransactionresults'])->middleware('auth')->name('printstransactionresults');
//BULK TRANSACTION
Route::get('mainstock/bulkform', [mainStockController::class, 'bulkform'])->middleware('auth')->name('bulkform');
Route::get('mainstock/bulkformadd', [mainStockController::class, 'bulkformadd'])->middleware('auth')->name('bulkformadd');
Route::post('mainstock/bulksend', [mainStockController::class, 'bulksend'])->middleware('auth')->name('bulksend');
Route::post('mainstock/bulkadd', [mainStockController::class, 'bulkadd'])->middleware('auth')->name('bulkadd');
//csv routes
Route::get('/StockTransactions/export', [StockTransactionsController::class, 'exportCsv'])->name('transactions.export.csv');
Route::get('/requests/export', [requestController::class, 'exportCsv'])->name('arequests.export.csv');
Route::get('/dispense/export', [DispenseController::class, 'exportCsv'])->name('dispense.export.csv');
Route::get('/rstock/export', [clincStockController::class, 'exportCsv'])->name('rstock.export.csv');
Route::get('/rstock/export', [clincStockController::class, 'exportrCsv'])->name('requeststock.export.csv');
//recieve stock
Route::get('/clinicstock/pendingstock', [clincStockController::class, 'showpending'])->middleware('auth')->name('pendingstock');
Route::get('/clinicstock/receivedstock', [clincStockController::class, 'receivedstock'])->middleware('auth')->name('receivedstock');
Route::patch('/clinicstock/pendingstock/update', [clincStockController::class, 'changestatus'])->middleware('auth')->name('changestatus');
Route::get('/clinicstock/search', [clincStockController::class,  'searchpstock'])->middleware('auth')->name('searchpstock');
Route::get('/clinicstock/search/item', [clincStockController::class,  'searchclinicstock'])->middleware('auth')->name('searchclinicstock');
Route::post('/clinicstock/search/rstock', [clincStockController::class, 'searchrstock'])->middleware('auth')->name('searchrstock');
//stocktransfer
Route::get('clinicstock/stocktransfer', [clincStockController::class, 'stocktransfer'])->middleware('auth')->name('stocktransfer');
Route::post('/clinicstock/stocktransfer', [clincStockController::class, 'savetransfer'])->middleware('auth')->name('savetransfer');
Route::get('/clinicstock/stocktransferpage', [clincStockController::class, 'gettransferpage'])->middleware('auth')->name('gettransferpage');
Route::post('clinicstock/bulktransfer', [clincStockController::class, 'bulktransfer'])->middleware('auth')->name('bulktransfer');

//search
Route::post('/drugtransfers/search', [clincStockController::class, 'searchtransfer'])->name('searchtransfer');

//making requesting stock
Route::get('/requeststock', [clincStockController::class, 'requeststock'])->middleware('auth')->name('requeststock');
Route::post('/requeststock/save', [clincStockController::class, 'saverequest'])->middleware('auth')->name('saverequest');

//handling requests stocks
Route::get('/requests', [requestController::class, 'showrequests'])->middleware('auth')->name('showrequests');
Route::get('/requests/approved', [requestController::class, 'showarequests'])->middleware('auth')->name('showarequests');
Route::get('/requests/denied', [requestController::class, 'showdrequests'])->middleware('auth')->name('showdrequests');
Route::get('/requests/all', [requestController::class, 'showallrequests'])->middleware('auth')->name('showallrequests');
//search request
Route::post('/requests/search', [requestController::class, 'searchrequests'])->middleware('auth')->name('searchrequests');
Route::post('/requests/view', [requestController::class, 'viewrequest'])->middleware('auth')->name('viewrequest');
Route::get('/clinicstock', [clincStockController::class, 'getclinicstock'])->middleware('auth')->name('getclinicstock');
//distribute stock
Route::patch('/mainstock/dis/{stock_item}', [requestController::class, 'approverequest'])->middleware('auth')->name('approverequest');
Route::post('/sendemail', [MailerController::class, 'sendEmail'])->name('sendEmail');
//admin options
Route::get('/admin/allclincistock', [Admincontroller::class, 'allclinicstocks'])->name('getallstocks');
Route::get('/admin/drug-report', [Admincontroller::class, 'showDrugReport'])->name('showDrugReport');
Route::post('/admin/drug-report-data/yearly', [Admincontroller::class, 'globalstats'])->name('globalstats');    
Route::get('/admin/allclincistockbatch', [Admincontroller::class, 'allclinicstocksbatch'])->name('batch');
Route::post('/admin/selecteclinicbatch', [Admincontroller::class, 'showclinicchartbatch'])->name('batchchart');
Route::get('/admin/allclincistockvalue', [Admincontroller::class, 'valuereport'])->name('valuereport');
Route::get('/report/download-csv', [Admincontroller::class, 'downloadCsv'])->name('report.download_csv');
Route::get('/report/download-csv/clinic', [Admincontroller::class, 'downloadclinicCsv'])->name('reportclinic.download_csv');
Route::get('/report/download-csv/clinic/value', [Admincontroller::class, 'downloadvalueCsv'])->name('downloadvalueCsv.download_csv');
Route::post('/admin/selecteclinicvalue', [Admincontroller::class, 'getvaluereport'])->name('getvaluereport');
Route::get('/account/Dispense/dispense/save', [DispenseController::class, 'dispensehistoryadmin'])->middleware('auth')->name('dishistoryadmin');
Route::post('/account/Dispense/dispense/his', [DispenseController::class, 'searchhisadmin'])->middleware('auth')->name('searchhisadmin');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/createclinic', [Admincontroller::class, 'getcreateclinicform'])->name('getcreateclinicform');
    Route::post('/admin/createclinic/save', [Admincontroller::class, 'createclinic'])->name('createclinic');
    Route::get('/registered', [RegisteredUserController::class, 'create'])->middleware('auth')->name('registerationform');
    Route::post('/registered', [RegisteredUserController::class, 'store'])->middleware('auth')->name('registered');
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);
    //get all users
    Route::get('admin/user', [Admincontroller::class, 'getuseroptions'])->name('getuseroptions');
    //delete users
    Route::post('admin/user', [Admincontroller::class, 'deleteuser'])->name('deleteuser');
    //reset user password
    Route::patch('/admin/user/reset/{id}', [Admincontroller::class, 'resetpassword'])->name(('resetpassword'));
    //adimnget all clinics

});
Route::get('/forbidden', function () {
    return view('layouts.forbidden');
})->name('forbidden');
//dispense stock to patientss
Route::post('/Dispense/dispense', [DispenseController::class, 'dispenseform'])->middleware('auth')->name('showdispenseform');
Route::post('/Dispense/dispense/save', [DispenseController::class, 'dispense'])->middleware('auth')->name('savedispense');
Route::get('/Dispense/dispense/save', [DispenseController::class, 'dispensehistory'])->middleware('auth')->name('dishistory');
Route::post('/Dispense/dispense/his', [DispenseController::class, 'searchhis'])->middleware('auth')->name('searchhis');
//patients
Route::get('/patients', [PatientController::class, 'showform'])->middleware('auth')->name('patientform');
Route::post('/patients/search', [PatientController::class, 'searchhis'])->middleware('auth')->name('showpatients');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
