<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UncertaintyController;
Route::post('/uncertainty/calculate',[UncertaintyController::class,'calculate']);
Route::post('/uncertainty/statistics',[UncertaintyController::class,'statistics']);
