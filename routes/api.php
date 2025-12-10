<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NursingApiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('nursing')->group(function () {
    Route::get('/seed-demo', [NursingApiController::class, 'seedDemoData']);
    Route::get('/settings', [NursingApiController::class, 'settings']);
    Route::post('/login', [NursingApiController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/dashboard', [NursingApiController::class, 'dashboard']);
        Route::get('/patients', [NursingApiController::class, 'patients']);
        Route::get('/patients/{id}', [NursingApiController::class, 'patientDetails']);
        Route::get('/visits/{id}/medications', [NursingApiController::class, 'medications']);
        Route::post('/visits/{id}/medications', [NursingApiController::class, 'administerMedication']);
        Route::get('/visits/{id}/vitals', [NursingApiController::class, 'vitals']); // Existing or needed?
        Route::post('/visits/{visitId}/vitals', [NursingApiController::class, 'storeVitals']);
        Route::post('/visits/{visitId}/notes', [NursingApiController::class, 'storeNote']);
    });
});
