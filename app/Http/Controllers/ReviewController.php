<?php
namespace App\Http\Controllers;
use App\Models\AnalysisRun;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
class ReviewController extends Controller
{
 public function __construct(private ApprovalService $service){}
 public function store(Request $request,AnalysisRun $analysis){
  $data=$request->validate(['decision'=>'required|in:APPROVED,REJECTED,REVISION_REQUIRED','comments'=>'nullable|string']); $data['reviewer']=session('lab_user.name');
  $this->service->review($analysis,$data['reviewer'],$data['decision'],$data['comments']??null); return redirect()->route('analysis.show',$analysis)->with('success','Review tersimpan.');
 }
}
