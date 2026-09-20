<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalysisController;
Route::get('/', fn () => redirect()->route('analysis.index'));
Route::get('/analysis', [AnalysisController::class,'index'])->name('analysis.index');
Route::post('/analysis/do', [AnalysisController::class,'calculateDo'])->name('analysis.do');
Route::post('/analysis/bod', [AnalysisController::class,'calculateBod'])->name('analysis.bod');
Route::post('/analysis/uncertainty', [AnalysisController::class,'calculateUncertainty'])->name('analysis.uncertainty');

use App\Http\Controllers\MasterController;
Route::get('/masters/samples', [MasterController::class,'samples'])->name('masters.samples');
Route::post('/masters/samples', [MasterController::class,'storeSample'])->name('masters.samples.store');
Route::delete('/masters/samples/{sample}', [MasterController::class,'deleteSample'])->name('masters.samples.delete');
Route::get('/masters/reagents', [MasterController::class,'reagents'])->name('masters.reagents');
Route::post('/masters/reagents', [MasterController::class,'storeReagent'])->name('masters.reagents.store');
Route::delete('/masters/reagents/{reagent}', [MasterController::class,'deleteReagent'])->name('masters.reagents.delete');
Route::get('/masters/instruments', [MasterController::class,'instruments'])->name('masters.instruments');
Route::post('/masters/instruments', [MasterController::class,'storeInstrument'])->name('masters.instruments.store');
Route::delete('/masters/instruments/{instrument}', [MasterController::class,'deleteInstrument'])->name('masters.instruments.delete');
