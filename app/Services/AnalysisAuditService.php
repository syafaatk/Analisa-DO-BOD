<?php
namespace App\Services;
use App\Models\AnalysisAudit;
use App\Models\AnalysisRun;
class AnalysisAuditService {
 public function log(AnalysisRun $run,string $action,?string $from=null,?string $to=null,?array $changes=null,?string $notes=null): void {
  AnalysisAudit::create(['analysis_run_id'=>$run->id,'actor'=>session('lab_user.name','system'),'action'=>$action,'from_status'=>$from,'to_status'=>$to,'changes'=>$changes,'notes'=>$notes]);
 }
}
