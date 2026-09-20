<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UncertaintyController;
use App\Http\Controllers\ReportController;
Route::post('/uncertainty/calculate',[UncertaintyController::class,'calculate']);
Route::post('/uncertainty/statistics',[UncertaintyController::class,'statistics']);
Route::get('/analysis/{id}/report',[ReportController::class,'show']);

use App\Http\Controllers\SampleController;
Route::apiResource('/samples',SampleController::class)->only(['index','store','show']);

use App\Http\Controllers\DashboardController;
Route::get('/dashboard', [DashboardController::class,'index']);

use App\Http\Controllers\ReviewController;
Route::post('/analysis/{analysis}/review',[ReviewController::class,'store']);

use App\Http\Controllers\AnalysisStatusController;
Route::patch('/analysis/{analysis}/status',[AnalysisStatusController::class,'update']);
