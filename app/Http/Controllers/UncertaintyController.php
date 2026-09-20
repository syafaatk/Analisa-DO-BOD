<?php
namespace App\Http\Controllers;
use App\Services\UncertaintyService;
use Illuminate\Http\Request;
class UncertaintyController extends Controller
{
 public function __construct(private UncertaintyService $service){}
 public function calculate(Request $request){
  $data=$request->validate(['parameter'=>'required|in:DO,BOD5','coverage_factor'=>'required|numeric|min:1','components'=>'required|array|min:1','components.*.name'=>'required|string','components.*.u'=>'required|numeric|min:0','components.*.sensitivity'=>'nullable|numeric']);
  return response()->json($this->service->build($data['components'],$data['coverage_factor']));
 }
 public function statistics(Request $request){
  $data=$request->validate(['values'=>'required|array|min:2','values.*'=>'required|numeric']);
  return response()->json($this->service->fromReplicates($data['values']));
 }
}
