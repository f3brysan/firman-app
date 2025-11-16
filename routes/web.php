<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MsPelangganController;
use App\Http\Controllers\BillingTransactionController;

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
    return view('welcome');
});

Route::resource('customers', MsPelangganController::class)->names('customers');
Route::resource('billing-transactions', BillingTransactionController::class)->names('billing-transactions');
Route::post('billing-transactions/import', [BillingTransactionController::class, 'import'])->name('billing-transactions.import');
Route::get('billing-transactions/export/{periode}', [BillingTransactionController::class, 'export'])->name('billing-transactions.export');