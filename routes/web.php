<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\NursingController;
use App\Http\Controllers\DoctorPortalController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientPortalController;
use App\Http\Controllers\PatientPageController;
use App\Http\Controllers\PatientWalletController;
use App\Http\Controllers\PharmacyPortalController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PortalAuthController;
use App\Http\Controllers\TheatreController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('patients', PatientController::class)->only(['index', 'create', 'store', 'show']);

    Route::get('wallets/{wallet}', [WalletController::class, 'show'])->name('wallets.show');
    Route::post('wallets/{wallet}/transactions', [WalletController::class, 'storeTransaction'])->name('wallets.transactions.store');

    Route::get('visits', [VisitController::class, 'index'])->name('visits.index');
    Route::get('visits/{visit}', [VisitController::class, 'show'])->name('visits.show');
    Route::patch('visits/{visit}/status', [VisitController::class, 'updateStatus'])->name('visits.status.update');

    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

    Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('prescriptions', [PharmacyController::class, 'index'])->name('prescriptions.index');
        Route::get('prescriptions/{prescription}', [PharmacyController::class, 'show'])->name('prescriptions.show');
        Route::patch('prescriptions/{prescription}', [PharmacyController::class, 'update'])->name('prescriptions.update');
    });

    Route::prefix('laboratory')->name('laboratory.')->group(function () {
        Route::get('tests', [LaboratoryController::class, 'index'])->name('tests.index');
        Route::get('tests/{labTest}', [LaboratoryController::class, 'show'])->name('tests.show');
        Route::patch('tests/{labTest}', [LaboratoryController::class, 'update'])->name('tests.update');
    });

    Route::prefix('nursing')->name('nursing.')->group(function () {
        Route::get('notes', [NursingController::class, 'index'])->name('notes.index');
        Route::post('notes', [NursingController::class, 'store'])->name('notes.store');
    });

    Route::prefix('theatre')->name('theatre.')->group(function () {
        Route::get('surgeries', [TheatreController::class, 'index'])->name('surgeries.index');
        Route::post('surgeries', [TheatreController::class, 'store'])->name('surgeries.store');
        Route::get('surgeries/{surgery}', [TheatreController::class, 'show'])->name('surgeries.show');
        Route::patch('surgeries/{surgery}', [TheatreController::class, 'update'])->name('surgeries.update');
    });

    Route::prefix('insurance')->name('insurance.')->group(function () {
        Route::get('claims', [InsuranceController::class, 'index'])->name('claims.index');
        Route::post('claims', [InsuranceController::class, 'store'])->name('claims.store');
        Route::get('claims/{claim}', [InsuranceController::class, 'show'])->name('claims.show');
        Route::patch('claims/{claim}', [InsuranceController::class, 'update'])->name('claims.update');
    });

    Route::get('accounts', [AccountsController::class, 'index'])->name('accounts.index');
    Route::post('accounts/records', [AccountsController::class, 'store'])->name('accounts.records.store');

    Route::get('administration', [AdministrationController::class, 'index'])->name('administration.index');
    Route::post('administration/departments', [AdministrationController::class, 'storeDepartment'])->name('administration.departments.store');
    Route::post('administration/services', [AdministrationController::class, 'storeService'])->name('administration.services.store');
});

Route::post('/logout', [PortalAuthController::class, 'destroy'])->middleware('auth')->name('logout');

$portalSlugs = array_keys(config('hms.portals', []));

Route::middleware('guest')->group(function () use ($portalSlugs) {
    foreach ($portalSlugs as $slug) {
        Route::redirect("/{$slug}", "/{$slug}/login")->name("portal.redirect.{$slug}");

        Route::get("/{$slug}/login", [PortalAuthController::class, 'create'])
            ->name("portal.login.{$slug}")
            ->defaults('portal', $slug);

        Route::post("/{$slug}/login", [PortalAuthController::class, 'store'])
            ->name("portal.login.{$slug}.store")
            ->defaults('portal', $slug);
    }

    Route::get('/login', fn () => redirect()->route('portal.login.admin'))->name('login');
    Route::redirect('/admin', '/admin/login');
    Route::redirect('/patient', '/patient/login');
});

Route::middleware(['auth', 'portal:patient'])->prefix('patient')->name('patient.portal.')->group(function () {
    Route::get('/dashboard', [PatientPortalController::class, 'dashboard'])->name('dashboard');
    Route::post('/wallet/deposit', [PatientWalletController::class, 'deposit'])->name('wallet.deposit');
    Route::get('/wallet/transactions', [PatientWalletController::class, 'transactions'])->name('wallet.transactions');
    Route::get('/wallet', [PatientPageController::class, 'wallet'])->name('wallet');
    Route::get('/visits', [PatientPageController::class, 'visits'])->name('visits');
    Route::get('/visits/request', [PatientPageController::class, 'requestVisit'])->name('visits.request');
    Route::post('/visits', [PatientPageController::class, 'storeVisit'])->name('visits.store');
    Route::get('/prescriptions', [PatientPageController::class, 'prescriptions'])->name('prescriptions');
    Route::get('/labs', [PatientPageController::class, 'labs'])->name('labs');
    Route::get('/care-notes', [PatientPageController::class, 'careNotes'])->name('care-notes');
    Route::get('/profile', [PatientPageController::class, 'profile'])->name('profile');
    Route::post('/profile', [PatientPageController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware(['auth', 'portal:doctor'])->prefix('doctor-portal')->name('doctor.portal.')->group(function () {
    Route::get('/dashboard', [DoctorPortalController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', 'portal:pharmacy'])->prefix('pharmacy-portal')->name('pharmacy.portal.')->group(function () {
    Route::get('/dashboard', [PharmacyPortalController::class, 'dashboard'])->name('dashboard');
});
