<?php
namespace App\Http\Controllers;
use App\Models\AnalysisRun;
use App\Models\AnalysisAudit;
use App\Models\BodDilution;
use App\Models\BodControl;
use Illuminate\Http\Request;
use App\Services\LaboratoryCalculationService;
use App\Services\BodEvaluationService;
use App\Services\AnalysisAuditService;
use App\Services\AnalysisWorkflowService;
class AnalysisRecordController extends Controller {
 public function show(AnalysisRun $analysis){$analysis->load(['bodDilutions','bodControls']);$audits=AnalysisAudit::where('analysis_run_id',$analysis->id)->latest()->get();return view('analysis.show',['analysis'=>$analysis,'audits'=>$audits]);}
 public function submit(AnalysisRun $analysis,AnalysisWorkflowService $workflow){$workflow->transition($analysis,'READY_FOR_REVIEW');return redirect()->route('analysis.show',$analysis)->with('success','Analisis dikirim untuk review.');}
 public function edit(AnalysisRun $analysis){if($analysis->status==='APPROVED')abort(403,'Analisis yang sudah approved tidak dapat diedit.');$analysis->load(['bodDilutions','bodControls']);return view('analysis.edit',['analysis'=>$analysis]);}
 public function update(Request $request,AnalysisRun $analysis,LaboratoryCalculationService $calc,BodEvaluationService $bodQc,AnalysisAuditService $audit){
  if($analysis->status==='APPROVED')abort(403);
  $base=$request->validate(['sample_code'=>'required|string|max:100','notes'=>'nullable|string']);
  $old=$analysis->inputs??[];$fromStatus=$analysis->status?:'DRAFT';
  if($analysis->parameter==='DO'){
   $data=$base+$request->validate(['thiosulfate_ml'=>'required|numeric|min:0.000001','thiosulfate_duplo_ml'=>'nullable|numeric|min:0.000001','normality'=>'required|numeric|min:0.000001','winkler_volume_ml'=>'required|numeric|min:2.000001','reagent_mnso4_ml'=>'required|numeric|min:0','reagent_alkali_ml'=>'required|numeric|min:0','aliquot_ml'=>'required|numeric|min:0.000001']);
   $newCalc=$calc->dissolvedOxygen($data['thiosulfate_ml'],$data['normality'],$data['winkler_volume_ml'],$data['reagent_mnso4_ml'],$data['reagent_alkali_ml'],$data['aliquot_ml']);
   if(!empty($data['thiosulfate_duplo_ml'])){$d=$calc->dissolvedOxygen($data['thiosulfate_duplo_ml'],$data['normality'],$data['winkler_volume_ml'],$data['reagent_mnso4_ml'],$data['reagent_alkali_ml'],$data['aliquot_ml']);$newCalc['duplo']=$d;$newCalc['rpd']=$calc->rpd($newCalc['result'],$d['result']);$newCalc['qc_status']=$newCalc['rpd']<=10?'PASS':'REVIEW';}
  } else {
   $data=$base+$request->validate([
    'a1'=>'required|numeric','a2'=>'required|numeric','b1'=>'required|numeric','b2'=>'required|numeric','vb'=>'required|numeric|min:0','vc'=>'required|numeric|min:0','p'=>'required|numeric|min:0.000001',
    'sample_ph'=>'nullable|numeric|min:0|max:14','sample_temperature'=>'nullable|numeric','sampling_at'=>'nullable|date','bod_incubation_start'=>'nullable|date','bod_incubation_end'=>'nullable|date','storage_temperature'=>'nullable|numeric','storage_hours'=>'nullable|numeric|min:0','interference_treatment'=>'nullable|string|max:255','gga_bod'=>'nullable|numeric',
    'dilutions'=>'nullable|array','dilutions.*.sample_volume_ml'=>'nullable|numeric|min:0','dilutions.*.final_volume_ml'=>'nullable|numeric|min:0.0001','dilutions.*.do_initial'=>'nullable|numeric','dilutions.*.do_final'=>'nullable|numeric','dilutions.*.incubation_temperature'=>'nullable|numeric','dilutions.*.incubation_hours'=>'nullable|numeric','dilutions.*.dilution_at'=>'nullable|date','dilutions.*.do_initial_at'=>'nullable|date','dilutions.*.do_final_at'=>'nullable|date'
   ]);
   $newCalc=$calc->bod5($data['a1'],$data['a2'],$data['b1'],$data['b2'],$data['vb'],$data['vc'],$data['p']);
   $newCalc['qc']=$bodQc->evaluate($data['a1'],$data['a2'],$data['b1'],$data['b2'],$data['p']);
   if(isset($data['gga_bod']))$newCalc['gga']=$bodQc->evaluateGga($data['gga_bod']);
   if(isset($data['storage_hours']))$newCalc['storage_status']=$bodQc->storageStatus($data['storage_hours']);
   $dilutions=$data['dilutions']??[];unset($data['dilutions']);
  }
  $inputs=$data;unset($inputs['notes']);
  $analysis->update(['sample_code'=>$data['sample_code'],'notes'=>$data['notes']??null,'inputs'=>$inputs,'calculation'=>$newCalc,'status'=>'DRAFT','submitted_at'=>null,'approved_by'=>null,'approved_at'=>null]);
  if($analysis->parameter==='BOD5'){
   $analysis->bodDilutions()->delete();$analysis->bodControls()->delete();
   foreach(($dilutions??[]) as $idx=>$d){if(!isset($d['do_initial'],$d['do_final'],$d['sample_volume_ml'],$d['final_volume_ml']))continue;$p=$d['final_volume_ml']>0?$d['sample_volume_ml']/$d['final_volume_ml']:0;$eval=$bodQc->evaluateDilution($d['do_initial'],$d['do_final'],$d['incubation_temperature']??20,$d['incubation_hours']??120);BodDilution::create(array_merge($d,['analysis_run_id'=>$analysis->id,'p'=>$p,'do_depletion'=>$d['do_initial']-$d['do_final'],'dilution_code'=>'D'.($idx+1),'selection_status'=>$eval['status']]));}
   if(isset($data['gga_bod'])){$g=$bodQc->evaluateGga($data['gga_bod']);BodControl::create(['analysis_run_id'=>$analysis->id,'control_type'=>'GGA','control_code'=>'GGA','bod_result'=>$data['gga_bod'],'expected_min'=>$g['lower'],'expected_max'=>$g['upper'],'acceptance_percent'=>30.5/198*100,'status'=>$g['status']]);}
  }
  $audit->log($analysis,'EDIT',$fromStatus,'DRAFT',['before'=>$old,'after'=>$inputs]);
  return redirect()->route('analysis.show',$analysis)->with('success','Data analisis diperbarui, QC dihitung ulang, dan status dikembalikan ke DRAFT.');
 }
 public function destroy(AnalysisRun $analysis,AnalysisAuditService $audit){if($analysis->status==='APPROVED')abort(403);$audit->log($analysis,'DELETE',$analysis->status,null);$analysis->delete();return redirect()->route('analysis.index')->with('success','Data analisis dihapus.');}
}