<?php
namespace App\Http\Controllers;

use App\Models\AnalysisRun;
use App\Services\LaboratoryCalculationService;
use App\Services\BodEvaluationService;
use App\Models\BodDilution;
use App\Models\BodControl;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
 public function __construct(private LaboratoryCalculationService $calc, private BodEvaluationService $bodQc){}
 public function index(){return view('analysis.index',['recent'=>AnalysisRun::latest()->limit(10)->get(),'doRuns'=>AnalysisRun::where('parameter','DO')->latest()->paginate(10,['*'],'do_page'),'bodRuns'=>AnalysisRun::where('parameter','BOD5')->latest()->paginate(10,['*'],'bod_page')]);}

 public function calculateDo(Request $request){
  $data=$request->validate(['sample_code'=>'required|string|max:100','thiosulfate_ml'=>'required|numeric|min:0.000001','thiosulfate_duplo_ml'=>'nullable|numeric|min:0.000001','normality'=>'required|numeric|min:0.000001','winkler_volume_ml'=>'required|numeric|min:2.000001','reagent_mnso4_ml'=>'required|numeric|min:0','reagent_alkali_ml'=>'required|numeric|min:0','aliquot_ml'=>'required|numeric|min:0.000001']);
  $calc=$this->calc->dissolvedOxygen($data['thiosulfate_ml'],$data['normality'],$data['winkler_volume_ml'],$data['reagent_mnso4_ml'],$data['reagent_alkali_ml'],$data['aliquot_ml']);
  $duplo=null; if(!empty($data['thiosulfate_duplo_ml'])) { $duplo=$this->calc->dissolvedOxygen($data['thiosulfate_duplo_ml'],$data['normality'],$data['winkler_volume_ml'],$data['reagent_mnso4_ml'],$data['reagent_alkali_ml'],$data['aliquot_ml']); $calc['duplo']=$duplo; $calc['rpd']=$this->calc->rpd($calc['result'],$duplo['result']); $calc['qc_status']=$calc['rpd']<=10?'PASS':'REVIEW'; } $this->saveRun($data['sample_code'],'DO','SNI 06-6989.14-2004',$data,$calc);
  return back()->with('do_result',$calc)->withInput();
 }

 public function calculateBod(Request $request){
  $data=$request->validate([
   'sample_code'=>'required|string|max:100','a1'=>'required|numeric','a2'=>'required|numeric','b1'=>'required|numeric','b2'=>'required|numeric','vb'=>'required|numeric|min:0','vc'=>'required|numeric|min:0','p'=>'required|numeric|min:0.000001',
   'sample_ph'=>'nullable|numeric|min:0|max:14','sample_temperature'=>'nullable|numeric','sampling_at'=>'nullable|date','bod_incubation_start'=>'nullable|date','bod_incubation_end'=>'nullable|date','storage_temperature'=>'nullable|numeric','storage_hours'=>'nullable|numeric|min:0','interference_treatment'=>'nullable|string|max:255',
   'gga_bod'=>'nullable|numeric',
   'dilutions'=>'nullable|array','dilutions.*.sample_volume_ml'=>'nullable|numeric|min:0','dilutions.*.final_volume_ml'=>'nullable|numeric|min:0.0001','dilutions.*.do_initial'=>'nullable|numeric','dilutions.*.do_final'=>'nullable|numeric','dilutions.*.incubation_temperature'=>'nullable|numeric','dilutions.*.incubation_hours'=>'nullable|numeric','dilutions.*.dilution_at'=>'nullable|date','dilutions.*.do_initial_at'=>'nullable|date','dilutions.*.do_final_at'=>'nullable|date'
  ]);
  $calc=$this->calc->bod5($data['a1'],$data['a2'],$data['b1'],$data['b2'],$data['vb'],$data['vc'],$data['p']);
  $calc['qc']=$this->bodQc->evaluate($data['a1'],$data['a2'],$data['b1'],$data['b2'],$data['p']);
  if(isset($data['gga_bod'])) $calc['gga']=$this->bodQc->evaluateGga($data['gga_bod']);
  if(isset($data['storage_hours'])) $calc['storage_status']=$this->bodQc->storageStatus($data['storage_hours']);
  $run=$this->saveRun($data['sample_code'],'BOD5','SNI 6989.72:2009',$data,$calc);
  foreach(($data['dilutions']??[]) as $idx=>$d){if(!isset($d['do_initial'],$d['do_final']))continue;$p=$d['final_volume_ml']>0?($d['sample_volume_ml']/$d['final_volume_ml']):0;$eval=$this->bodQc->evaluateDilution($d['do_initial'],$d['do_final'],$d['incubation_temperature']??20,$d['incubation_hours']??120);BodDilution::create(array_merge($d,['analysis_run_id'=>$run->id,'p'=>$p,'do_depletion'=>$d['do_initial']-$d['do_final'],'dilution_code'=>'D'.($idx+1),'selection_status'=>$eval['status']]));}
  if(isset($data['gga_bod'])){ $g=$this->bodQc->evaluateGga($data['gga_bod']); BodControl::create(['analysis_run_id'=>$run->id,'control_type'=>'GGA','control_code'=>'GGA','bod_result'=>$data['gga_bod'],'expected_min'=>$g['lower'],'expected_max'=>$g['upper'],'status'=>$g['status']]); }
  return back()->with('bod_result',$calc)->withInput();
 }

 public function calculateUncertainty(Request $request){
  $data=$request->validate(['precision_sd'=>'required|numeric|min:0','precision_n'=>'required|integer|min:2','bias_sd'=>'nullable|numeric|min:0','bias_n'=>'nullable|integer|min:2','coverage_factor'=>'required|numeric|min:1']);
  return back()->with('uncertainty_result',$this->calc->topDownUncertainty($data['precision_sd'],$data['precision_n'],$data['bias_sd']??null,$data['bias_n']??null,$data['coverage_factor']))->withInput();
 }

 private function saveRun(string $sample,string $parameter,string $method,array $inputs,array $calculation): AnalysisRun{
  return AnalysisRun::create(['sample_code'=>$sample,'parameter'=>$parameter,'method_version'=>$method,'analyst'=>session('lab_user.name'),'analysed_at'=>now(),'inputs'=>$inputs,'calculation'=>$calculation,'status'=>'DRAFT']);
 }
}
