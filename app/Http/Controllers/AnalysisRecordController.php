<?php
namespace App\Http\Controllers;
use App\Models\AnalysisRun;
use Illuminate\Http\Request;
use App\Services\LaboratoryCalculationService;
use App\Services\AnalysisAuditService;
use App\Services\AnalysisWorkflowService;
class AnalysisRecordController extends Controller {
 public function show(AnalysisRun $analysis){$analysis->load(['bodDilutions','bodControls']); $audits=\App\Models\AnalysisAudit::where('analysis_run_id',$analysis->id)->latest()->get(); return view('analysis.show',['analysis'=>$analysis,'audits'=>$audits]);}
 public function submit(AnalysisRun $analysis, AnalysisWorkflowService $workflow){$workflow->transition($analysis,'READY_FOR_REVIEW'); return redirect()->route('analysis.show',$analysis)->with('success','Analisis dikirim untuk review.');}
 public function edit(AnalysisRun $analysis){if($analysis->status==='APPROVED') abort(403,'Analisis yang sudah approved tidak dapat diedit.'); return view('analysis.edit',['analysis'=>$analysis]);}
 public function update(Request $request,AnalysisRun $analysis, LaboratoryCalculationService $calc, AnalysisAuditService $audit){
  if($analysis->status==='APPROVED') abort(403);
  $data=$request->validate(['sample_code'=>'required|string|max:100','notes'=>'nullable|string']);
  $old=$analysis->inputs ?? []; $fromStatus=$analysis->status ?: 'DRAFT';
  if($analysis->parameter==='DO'){
   $data += $request->validate(['thiosulfate_ml'=>'required|numeric|min:0.000001','thiosulfate_duplo_ml'=>'nullable|numeric|min:0.000001','normality'=>'required|numeric|min:0.000001','winkler_volume_ml'=>'required|numeric|min:2.000001','reagent_mnso4_ml'=>'required|numeric|min:0','reagent_alkali_ml'=>'required|numeric|min:0','aliquot_ml'=>'required|numeric|min:0.000001']);
   $newCalc=$calc->dissolvedOxygen($data['thiosulfate_ml'],$data['normality'],$data['winkler_volume_ml'],$data['reagent_mnso4_ml'],$data['reagent_alkali_ml'],$data['aliquot_ml']);
   if(!empty($data['thiosulfate_duplo_ml'])){$d=$calc->dissolvedOxygen($data['thiosulfate_duplo_ml'],$data['normality'],$data['winkler_volume_ml'],$data['reagent_mnso4_ml'],$data['reagent_alkali_ml'],$data['aliquot_ml']);$newCalc['duplo']=$d;$newCalc['rpd']=$calc->rpd($newCalc['result'],$d['result']);$newCalc['qc_status']=$newCalc['rpd']<=10?'PASS':'REVIEW';}
  } else {
   $data += $request->validate(['a1'=>'required|numeric','a2'=>'required|numeric','b1'=>'required|numeric','b2'=>'required|numeric','vb'=>'required|numeric|min:0','vc'=>'required|numeric|min:0','p'=>'required|numeric|min:0.000001']);
   $newCalc=$calc->bod5($data['a1'],$data['a2'],$data['b1'],$data['b2'],$data['vb'],$data['vc'],$data['p']);
  }
  $inputs=$data; unset($inputs['notes']);
  $analysis->update(['sample_code'=>$data['sample_code'],'notes'=>$data['notes']??null,'inputs'=>$inputs,'calculation'=>$newCalc,'status'=>'DRAFT','submitted_at'=>null]);
  $audit->log($analysis,'EDIT',$fromStatus,'DRAFT',['before'=>$old,'after'=>$inputs]);
  return redirect()->route('analysis.show',$analysis)->with('success','Data analisis diperbarui dan dikembalikan ke DRAFT.');
 }
 public function destroy(AnalysisRun $analysis, AnalysisAuditService $audit){if($analysis->status==='APPROVED') abort(403);$audit->log($analysis,'DELETE',$analysis->status,null);$analysis->delete();return redirect()->route('analysis.index')->with('success','Data analisis dihapus.');}
}
