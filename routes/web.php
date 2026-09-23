<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\AnalysisRecordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientManagementController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\LaboratoryContextController;
use App\Http\Controllers\LaboratoryManagementController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserManagementController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/client/login', [ClientAuthController::class, 'showLogin'])->name('client.login');
Route::post('/client/login', [ClientAuthController::class, 'login'])->name('client.login.submit');
Route::post('/client/logout', [ClientAuthController::class, 'logout'])->name('client.logout');

Route::middleware('client.auth')->prefix('client')->group(function () {
    Route::get('/', [ClientPortalController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/reports/{analysis}', [ClientPortalController::class, 'report'])->name('client.report');
    Route::get('/reports/{analysis}/print', [ClientPortalController::class, 'download'])->name('client.report.print');
});

Route::middleware('lab.auth')->group(function () {
    Route::middleware('lab.context')->group(function () {
        Route::get('/', fn () => redirect()->route('analysis.index'));

        Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
        Route::get('/analysis/do', [AnalysisController::class, 'doPage'])->name('analysis.do.page');
        Route::get('/analysis/bod', [AnalysisController::class, 'bodPage'])->name('analysis.bod.page');
        Route::get('/analysis/uncertainty', [AnalysisController::class, 'uncertaintyPage'])->name('analysis.uncertainty.page');
        Route::post('/analysis/do', [AnalysisController::class, 'calculateDo'])->name('analysis.do');
        Route::post('/analysis/bod', [AnalysisController::class, 'calculateBod'])->name('analysis.bod');
        Route::post('/analysis/uncertainty', [AnalysisController::class, 'calculateUncertainty'])->name('analysis.uncertainty');

        Route::get('/analysis/{analysis}', [AnalysisRecordController::class, 'show'])->name('analysis.show');
        Route::post('/analysis/{analysis}/submit', [AnalysisRecordController::class, 'submit'])->middleware('role:analyst,admin,super_admin')->name('analysis.submit');
        Route::get('/analysis/{analysis}/edit', [AnalysisRecordController::class, 'edit'])->middleware('role:analyst,admin,super_admin')->name('analysis.edit');
        Route::put('/analysis/{analysis}', [AnalysisRecordController::class, 'update'])->middleware('role:analyst,admin,super_admin')->name('analysis.update');
        Route::delete('/analysis/{analysis}', [AnalysisRecordController::class, 'destroy'])->middleware('role:analyst,admin,super_admin')->name('analysis.delete');
        Route::post('/analysis/{analysis}/review', [ReviewController::class, 'store'])->middleware('role:supervisor,admin,super_admin')->name('analysis.review');
        Route::get('/analysis/{analysis}/report', [ReportController::class, 'show'])->name('analysis.report');

        Route::get('/masters/samples', [MasterController::class, 'samples'])->name('masters.samples');
        Route::post('/masters/samples', [MasterController::class, 'storeSample'])->name('masters.samples.store');
        Route::delete('/masters/samples/{sample}', [MasterController::class, 'deleteSample'])->name('masters.samples.delete');
        Route::put('/masters/samples/{sample}', [MasterController::class, 'updateSample'])->name('masters.samples.update');
        Route::get('/masters/reagents', [MasterController::class, 'reagents'])->name('masters.reagents');
        Route::post('/masters/reagents', [MasterController::class, 'storeReagent'])->name('masters.reagents.store');
        Route::delete('/masters/reagents/{reagent}', [MasterController::class, 'deleteReagent'])->name('masters.reagents.delete');
        Route::put('/masters/reagents/{reagent}', [MasterController::class, 'updateReagent'])->name('masters.reagents.update');
        Route::get('/masters/instruments', [MasterController::class, 'instruments'])->name('masters.instruments');
        Route::post('/masters/instruments', [MasterController::class, 'storeInstrument'])->name('masters.instruments.store');
        Route::delete('/masters/instruments/{instrument}', [MasterController::class, 'deleteInstrument'])->name('masters.instruments.delete');
        Route::put('/masters/instruments/{instrument}', [MasterController::class, 'updateInstrument'])->name('masters.instruments.update');

        Route::middleware('role:admin,super_admin')->prefix('admin')->group(function () {
            Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users');
            Route::post('/users', [UserManagementController::class, 'store'])->name('admin.users.store');
            Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
            Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.delete');
            Route::get('/clients', [ClientManagementController::class, 'index'])->name('admin.clients');
            Route::post('/clients', [ClientManagementController::class, 'store'])->name('admin.clients.store');
            Route::put('/clients/{client}', [ClientManagementController::class, 'update'])->name('admin.clients.update');
            Route::delete('/clients/{client}', [ClientManagementController::class, 'destroy'])->name('admin.clients.delete');
        });
    });

    Route::middleware('role:super_admin')->prefix('superadmin')->group(function () {
        Route::get('/laboratories', [LaboratoryManagementController::class, 'index'])->name('superadmin.laboratories');
        Route::post('/laboratories', [LaboratoryManagementController::class, 'store'])->name('superadmin.laboratories.store');
        Route::put('/laboratories/{laboratory}', [LaboratoryManagementController::class, 'update'])->name('superadmin.laboratories.update');
        Route::post('/laboratories/{laboratory}/switch', [LaboratoryContextController::class, 'switch'])->name('superadmin.laboratories.switch');
        Route::post('/laboratories/clear-context', [LaboratoryContextController::class, 'clear'])->name('superadmin.laboratories.clear');
    });
});