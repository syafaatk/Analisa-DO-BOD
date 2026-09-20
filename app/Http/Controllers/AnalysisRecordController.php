<?php
namespace App\Http\Controllers;
use App\Models\AnalysisRun;
use Illuminate\Http\Request;
class AnalysisRecordController extends Controller {
 public function show(AnalysisRun $analysis){return view('analysis.show',['analysis'=>$analysis]);}
 public function edit(AnalysisRun $analysis){if($analysis->status==='APPROVED') abort(403,'Analisis yang sudah approved tidak dapat diedit.'); return view('analysis.edit',['analysis'=>$analysis]);}
 public function update(Request $request,AnalysisRun $analysis){
  if($analysis->status==='APPROVED') abort(403);
  $data=$request->validate(['notes'=>'nullable|string','sample_code'=>'required|string|max:100']);
  $analysis->update(['sample_code'=>$data['sample_code'],'notes'=>$data['notes']??null]);
  return redirect()->route('analysis.show',$analysis)->with('success','Data analisis diperbarui.');
 }
 public function destroy(AnalysisRun $analysis){if($analysis->status==='APPROVED') abort(403);$analysis->delete();return redirect()->route('analysis.index')->with('success','Data analisis dihapus.');}
}
