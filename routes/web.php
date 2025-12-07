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

    Route::prefix('admin/pharmacy')->name('admin.pharmacy.')->group(function () {
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
    Route::post('/wallet/simulate-credit', [PatientWalletController::class, 'simulateCredit'])->name('wallet.simulate');
    Route::get('/wallet/transactions', [PatientWalletController::class, 'transactions'])->name('wallet.transactions');
    Route::post('/wallet/generate-account', [PatientWalletController::class, 'generateAccount'])->name('wallet.generate');
    Route::get('/wallet', [PatientPageController::class, 'wallet'])->name('wallet');
    Route::get('/visits', [PatientPageController::class, 'visits'])->name('visits');
    Route::get('/visits/request', [PatientPageController::class, 'requestVisit'])->name('visits.request');
    Route::post('/visits', [PatientPageController::class, 'storeVisit'])->name('visits.store');
    Route::get('/visits/{visit}', [PatientPageController::class, 'showVisit'])->name('visits.show');
    Route::patch('/visits/{visit}', [PatientPageController::class, 'updateVisit'])->name('visits.update');
    Route::post('/visits/{visit}/cancel', [PatientPageController::class, 'cancelVisit'])->name('visits.cancel');
    Route::get('/prescriptions', [PatientPageController::class, 'prescriptions'])->name('prescriptions');
    Route::get('/prescriptions/{prescription}', [PatientPageController::class, 'showPrescription'])->name('prescriptions.show');
    Route::get('/labs', [PatientPageController::class, 'labs'])->name('labs');
    Route::get('/labs/{labTest}', [PatientPageController::class, 'showLab'])->name('labs.show');
    Route::get('/care-notes', [PatientPageController::class, 'careNotes'])->name('care-notes');
    Route::get('/profile', [PatientPageController::class, 'profile'])->name('profile');
    Route::post('/profile', [PatientPageController::class, 'updateProfile'])->name('profile.update');
    Route::get('/claims', [PatientPageController::class, 'claims'])->name('claims');
});

Route::middleware(['auth', 'portal:doctor'])->prefix('doctor')->name('doctor.portal.')->group(function () {
    Route::get('/dashboard', [DoctorPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/patients', [DoctorPortalController::class, 'patients'])->name('patients.index');
    Route::get('/patients/{patient}', [DoctorPortalController::class, 'showPatient'])->name('patients.show');
    Route::get('/visits/{visit}', [DoctorPortalController::class, 'showVisit'])->name('visits.show');
    Route::post('/visits/{visit}/prescribe', [DoctorPortalController::class, 'storePrescription'])->name('visits.prescribe');
    Route::post('/visits/{visit}/lab-test', [DoctorPortalController::class, 'storeLabTest'])->name('visits.lab-test');
    Route::post('/visits/{visit}/diagnosis', [DoctorPortalController::class, 'storeDiagnosis'])->name('visits.diagnosis');
    Route::post('/visits/{visit}/admit', [DoctorPortalController::class, 'storeAdmission'])->name('visits.admit');
    Route::post('/visits/{visit}/refer', [DoctorPortalController::class, 'storeReferral'])->name('visits.refer');
    Route::post('/visits/{visit}/surgery', [DoctorPortalController::class, 'storeSurgery'])->name('visits.surgery');
    Route::get('/drugs/search', [DoctorPortalController::class, 'searchDrugs'])->name('drugs.search');

    // New Menu Routes
    Route::get('/queue', [DoctorPortalController::class, 'queue'])->name('queue');
    Route::get('/appointments', [DoctorPortalController::class, 'appointments'])->name('appointments');
    Route::get('/prescriptions', [DoctorPortalController::class, 'prescriptions'])->name('prescriptions');
    Route::get('/labs', [DoctorPortalController::class, 'labs'])->name('labs');
    Route::get('/nursing-notes', [DoctorPortalController::class, 'nursingNotes'])->name('nursing-notes');
    Route::get('/theatre-requests', [DoctorPortalController::class, 'theatreRequests'])->name('theatre-requests');
});

Route::middleware(['auth', 'portal:pharmacy'])->prefix('pharmacy')->name('pharmacy.portal.')->group(function () {
    Route::get('/dashboard', [PharmacyPortalController::class, 'dashboard'])->name('dashboard');
    
    // Prescriptions
    Route::get('/prescriptions', [PharmacyPortalController::class, 'index'])->name('prescriptions.index');
    Route::get('/prescriptions/{prescription}', [PharmacyPortalController::class, 'show'])->name('prescriptions.show');
    Route::post('/prescriptions/{prescription}/dispense', [PharmacyPortalController::class, 'dispense'])->name('dispense');
    Route::get('/prescriptions/{prescription}/dispense', fn($prescription) => redirect()->route('pharmacy.portal.prescriptions.show', $prescription));

    // Inventory
    Route::get('/inventory', [PharmacyPortalController::class, 'inventory'])->name('inventory.index');
    Route::post('/inventory', [PharmacyPortalController::class, 'store'])->name('inventory.store');
    Route::post('/inventory/{drug}/update', [PharmacyPortalController::class, 'update'])->name('inventory.update');
});
