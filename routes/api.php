<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UncertaintyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;

/*
 | Legacy JSON endpoints are session-protected because the application uses
 | laboratory session context rather than stateless API tokens.
 | React uses the web routes for CSRF-protected same-origin mutations.
*/
Route::middleware(['web','lab.auth','lab.context'])->group(function () {
    Route::post('/uncertainty/calculate',[UncertaintyController::class,'calculate']);
    Route::post('/uncertainty/statistics',[UncertaintyController::class,'statistics']);
    Route::get('/analysis/{id}/report',[ReportController::class,'show']);
    Route::apiResource('/samples',SampleController::class)->only(['index','store','show']);
    Route::get('/dashboard',[DashboardController::class,'index']);
    Route::post('/analysis/{analysis}/review',[ReviewController::class,'store'])->middleware('role:supervisor,admin');
});
