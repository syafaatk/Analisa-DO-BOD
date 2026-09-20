<?php
namespace App\Services;
use App\Models\AnalysisRun;use App\Models\ClientUser;use App\Models\ReportNotification;use App\Notifications\ApprovedReportNotification;use Illuminate\Support\Facades\Notification;
class ReportNotificationService {
 public function sendApproved(AnalysisRun $run):void{
  if(!$run->client_id || $run->status!=='APPROVED')return;
  $users=ClientUser::where('client_id',$run->client_id)->where('active',true)->get();
  foreach($users as $u){$n=ReportNotification::create(['laboratory_id'=>$run->laboratory_id,'analysis_run_id'=>$run->id,'client_id'=>$run->client_id,'recipient'=>$u->email,'type'=>'REPORT_APPROVED','status'=>'PENDING']);try{Notification::route('mail',$u->email)->notify(new ApprovedReportNotification($run));$n->update(['status'=>'SENT','sent_at'=>now()]);}catch(\Throwable $e){$n->update(['status'=>'FAILED','error'=>$e->getMessage()]);}}
 }
}