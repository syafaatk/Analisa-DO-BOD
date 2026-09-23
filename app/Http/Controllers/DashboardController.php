<?php
namespace App\Http\Controllers;
use App\Models\AnalysisRun;
use App\Models\Sample;
class DashboardController extends Controller
{
 public function index(){
  return response()->json([
   'samples'=>Sample::count(),
   'analyses'=>AnalysisRun::count(),
   'do_analyses'=>AnalysisRun::where('parameter','DO')->count(),
   'bod_analyses'=>AnalysisRun::where('parameter','BOD5')->count(),
   'latest'=>AnalysisRun::latest()->limit(10)->get(['id','sample_code','parameter','method_version','analyst','analysed_at'])
  ]);
 }
}
