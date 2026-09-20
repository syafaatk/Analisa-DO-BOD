<?php
namespace App\Services;
use App\Models\AnalysisReview;
use App\Models\AnalysisRun;
use InvalidArgumentException;
class ApprovalService
{
 public function review(AnalysisRun $run,string $reviewer,string $decision,?string $comments=null): AnalysisReview
 {
  $decision=strtoupper($decision);
  if(!in_array($decision,['APPROVED','REJECTED','REVISION_REQUIRED'],true)) throw new InvalidArgumentException('Decision tidak valid.');
  if($decision==='APPROVED'){
   $run->update(['status'=>'APPROVED','approved_by'=>$reviewer,'approved_at'=>now()]);
  } elseif($decision==='REJECTED') $run->update(['status'=>'REJECTED']);
  else $run->update(['status'=>'REVISION_REQUIRED']);
  return AnalysisReview::create(['analysis_run_id'=>$run->id,'reviewer'=>$reviewer,'decision'=>$decision,'comments'=>$comments,'reviewed_at'=>now()]);
 }
}
