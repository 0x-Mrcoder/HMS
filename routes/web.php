<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('patients', PatientController::class)->only(['index', 'create', 'store', 'show']);

Route::get('wallets/{wallet}', [WalletController::class, 'show'])->name('wallets.show');
Route::post('wallets/{wallet}/transactions', [WalletController::class, 'storeTransaction'])->name('wallets.transactions.store');

Route::get('visits', [VisitController::class, 'index'])->name('visits.index');
Route::get('visits/{visit}', [VisitController::class, 'show'])->name('visits.show');
Route::patch('visits/{visit}/status', [VisitController::class, 'updateStatus'])->name('visits.status.update');

Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

Route::view('/login', 'auth.login')->name('login');
