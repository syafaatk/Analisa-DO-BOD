<?php
namespace App\Http\Controllers;
use App\Models\AnalysisRun;
use Illuminate\Http\Request;
class AnalysisStatusController extends Controller
{
 public function update(Request $request,AnalysisRun $analysis){
  $data=$request->validate(['status'=>'required|in:DRAFT,READY_FOR_REVIEW,REVISION_REQUIRED,APPROVED,REJECTED']);
  $analysis->update(['status'=>$data['status']]);
  return response()->json($analysis->fresh());
 }
}
