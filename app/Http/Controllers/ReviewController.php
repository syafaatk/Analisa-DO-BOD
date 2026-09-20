<?php
namespace App\Http\Controllers;
use App\Models\AnalysisRun;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
class ReviewController extends Controller
{
 public function __construct(private ApprovalService $service){}
 public function store(Request $request,AnalysisRun $analysis){
  $data=$request->validate(['reviewer'=>'required|string|max:255','decision'=>'required|in:APPROVED,REJECTED,REVISION_REQUIRED','comments'=>'nullable|string']);
  return response()->json($this->service->review($analysis,$data['reviewer'],$data['decision'],$data['comments']??null));
 }
}
