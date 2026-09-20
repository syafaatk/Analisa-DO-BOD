<?php
namespace App\Services;
use App\Models\AnalysisRun;
use InvalidArgumentException;
class AnalysisWorkflowService
{
 public function transition(AnalysisRun $run,string $to): AnalysisRun
 {
  $from=$run->status ?: 'DRAFT';
  $allowed=[
   'DRAFT'=>['READY_FOR_REVIEW'],
   'READY_FOR_REVIEW'=>['REVISION_REQUIRED','APPROVED','REJECTED'],
   'REVISION_REQUIRED'=>['READY_FOR_REVIEW'],
   'REJECTED'=>['DRAFT'],
   'APPROVED'=>[],
  ];
  if(!in_array($to,$allowed[$from]??[],true)) throw new InvalidArgumentException("Transition {$from} -> {$to} tidak diizinkan.");
  $run->update(['status'=>$to,'submitted_at'=>$to==='READY_FOR_REVIEW'?now():$run->submitted_at]);
  return $run->fresh();
 }
}
