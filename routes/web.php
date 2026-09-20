<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalysisController;
Route::get('/', fn () => redirect()->route('analysis.index'));
Route::get('/analysis', [AnalysisController::class,'index'])->name('analysis.index');
Route::post('/analysis/do', [AnalysisController::class,'calculateDo'])->name('analysis.do');
Route::post('/analysis/bod', [AnalysisController::class,'calculateBod'])->name('analysis.bod');
Route::post('/analysis/uncertainty', [AnalysisController::class,'calculateUncertainty'])->name('analysis.uncertainty');
