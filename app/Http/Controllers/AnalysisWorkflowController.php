<?php
namespace App\Http\Controllers;
use App\Models\AnalysisRun;
use App\Services\AnalysisWorkflowService;
use Illuminate\Http\Request;
class AnalysisWorkflowController extends Controller
{
 public function __construct(private AnalysisWorkflowService $workflow){}
 public function transition(Request $request,AnalysisRun $analysis){
  $data=$request->validate(['status'=>'required|in:READY_FOR_REVIEW,REVISION_REQUIRED,APPROVED,REJECTED,DRAFT']);
  return response()->json($this->workflow->transition($analysis,$data['status']));
 }
}
