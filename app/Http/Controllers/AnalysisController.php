<?php
namespace App\Http\Controllers;
use App\Services\LaboratoryCalculationService;
use Illuminate\Http\Request;
class AnalysisController extends Controller
{
    public function __construct(private LaboratoryCalculationService $calc){}
    public function index(){return view('analysis.index');}
    public function calculateDo(Request $request){
        $data=$request->validate(['thiosulfate_ml'=>'required|numeric|min:0.000001','normality'=>'required|numeric|min:0.000001','winkler_volume_ml'=>'required|numeric|min:2.000001','reagent_mnso4_ml'=>'required|numeric|min:0','reagent_alkali_ml'=>'required|numeric|min:0','aliquot_ml'=>'required|numeric|min:0.000001']);
        return back()->with('do_result',$this->calc->dissolvedOxygen(...array_values($data)))->withInput();
    }
    public function calculateBod(Request $request){
        $data=$request->validate(['a1'=>'required|numeric','a2'=>'required|numeric','b1'=>'required|numeric','b2'=>'required|numeric','vb'=>'required|numeric|min:0','vc'=>'required|numeric|min:0','p'=>'required|numeric|min:0.000001']);
        return back()->with('bod_result',$this->calc->bod5(...array_values($data)))->withInput();
    }
    public function calculateUncertainty(Request $request){
        $data=$request->validate(['precision_sd'=>'required|numeric|min:0','precision_n'=>'required|integer|min:2','bias_sd'=>'nullable|numeric|min:0','bias_n'=>'nullable|integer|min:2','coverage_factor'=>'required|numeric|min:1']);
        return back()->with('uncertainty_result',$this->calc->topDownUncertainty(...array_values($data)))->withInput();
    }
}
