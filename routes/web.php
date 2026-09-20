<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalysisController;
Route::middleware('lab.auth')->group(function(){
Route::get('/', fn () => redirect()->route('analysis.index'));
Route::get('/analysis', [AnalysisController::class,'index'])->name('analysis.index');
Route::post('/analysis/do', [AnalysisController::class,'calculateDo'])->name('analysis.do');
Route::post('/analysis/bod', [AnalysisController::class,'calculateBod'])->name('analysis.bod');
Route::post('/analysis/uncertainty', [AnalysisController::class,'calculateUncertainty'])->name('analysis.uncertainty');
});

use App\Http\Controllers\MasterController;
Route::middleware('lab.auth')->group(function(){
Route::get('/masters/samples', [MasterController::class,'samples'])->name('masters.samples');
Route::post('/masters/samples', [MasterController::class,'storeSample'])->name('masters.samples.store');
Route::delete('/masters/samples/{sample}', [MasterController::class,'deleteSample'])->name('masters.samples.delete');
Route::get('/masters/reagents', [MasterController::class,'reagents'])->name('masters.reagents');
Route::post('/masters/reagents', [MasterController::class,'storeReagent'])->name('masters.reagents.store');
Route::delete('/masters/reagents/{reagent}', [MasterController::class,'deleteReagent'])->name('masters.reagents.delete');
Route::get('/masters/instruments', [MasterController::class,'instruments'])->name('masters.instruments');
Route::post('/masters/instruments', [MasterController::class,'storeInstrument'])->name('masters.instruments.store');
Route::delete('/masters/instruments/{instrument}', [MasterController::class,'deleteInstrument'])->name('masters.instruments.delete');
});

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;
Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login.submit');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');
Route::middleware('lab.auth')->group(function(){
 Route::middleware('role:admin')->prefix('admin')->group(function(){Route::get('/users',[UserManagementController::class,'index'])->name('admin.users');Route::post('/users',[UserManagementController::class,'store'])->name('admin.users.store');Route::put('/users/{user}',[UserManagementController::class,'update'])->name('admin.users.update');Route::delete('/users/{user}',[UserManagementController::class,'destroy'])->name('admin.users.delete');});
});

use App\Http\Controllers\AnalysisRecordController;
use App\Http\Controllers\ReviewController;
Route::middleware('lab.auth')->group(function(){
 Route::get('/analysis/{analysis}',[AnalysisRecordController::class,'show'])->name('analysis.show');
 Route::get('/analysis/{analysis}/edit',[AnalysisRecordController::class,'edit'])->middleware('role:analyst,admin')->name('analysis.edit');
 Route::put('/analysis/{analysis}',[AnalysisRecordController::class,'update'])->middleware('role:analyst,admin')->name('analysis.update');
 Route::delete('/analysis/{analysis}',[AnalysisRecordController::class,'destroy'])->middleware('role:analyst,admin')->name('analysis.delete');
 Route::post('/analysis/{analysis}/review',[ReviewController::class,'store'])->middleware('role:supervisor,admin')->name('analysis.review');
});
