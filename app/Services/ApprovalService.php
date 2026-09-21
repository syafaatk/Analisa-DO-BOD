<?php
namespace App\Services;
use App\Models\AnalysisReview;
use App\Models\AnalysisRun;
use App\Services\AnalysisAuditService;
use App\Services\ReportNotificationService;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ApprovalService {
 public function __construct(private AnalysisAuditService $audit,private ReportNotificationService $notifications){}
 public function review(AnalysisRun $run,string $reviewer,string $decision,?string $comments=null):AnalysisReview{
  $decision=strtoupper($decision);
  $from=$run->status?:'DRAFT';
  if(!in_array($decision,['APPROVED','REJECTED','REVISION_REQUIRED'],true)) throw new InvalidArgumentException('Decision tidak valid.');
  if($from!=='READY_FOR_REVIEW') throw new InvalidArgumentException('Review hanya dapat dilakukan pada analisis dengan status READY_FOR_REVIEW.');
  if($decision==='APPROVED'){
   if(empty($run->report_number)) $run->report_number=self::generateReportNumber();
   $run->update(['status'=>'APPROVED','approved_by'=>$reviewer,'approved_at'=>now(),'report_number'=>$run->report_number]);
   $this->notifications->sendApproved($run);
  }elseif($decision==='REJECTED'){
   $run->update(['status'=>'REJECTED']);
  }else{
   $run->update(['status'=>'REVISION_REQUIRED']);
  }
  $this->audit->log($run,'REVIEW',$from,$run->status,null,$comments);
  return AnalysisReview::create(['analysis_run_id'=>$run->id,'reviewer'=>$reviewer,'decision'=>$decision,'comments'=>$comments,'reviewed_at'=>now()]);
 }
 public static function generateReportNumber(): string {
  return 'LAP-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
 }
}