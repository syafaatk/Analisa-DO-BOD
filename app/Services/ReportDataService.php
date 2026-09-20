<?php
namespace App\Services;
use App\Models\AnalysisRun;
class ReportDataService {
 public function result(string $id): array {
  $run=AnalysisRun::with(['bodDilutions','bodControls','audits'])->findOrFail($id);
  return [
   'id'=>$run->id,
   'report_number'=>$run->report_number,'sample_code'=>$run->sample_code,'parameter'=>$run->parameter,'method'=>$run->method_version,'analyst'=>$run->analyst,
   'analysed_at'=>$run->analysed_at?->toIso8601String(),'status'=>$run->status,'approved_by'=>$run->approved_by,'approved_at'=>$run->approved_at?->toIso8601String(),
   'metadata'=>['sample_matrix'=>$run->sample_matrix,'batch_code'=>$run->batch_code,'instrument_code'=>$run->instrument_code,'reagent_lot'=>$run->reagent_lot],
   'inputs'=>$run->inputs,'calculation'=>$run->calculation,'uncertainty'=>$run->uncertainty,
   'bod_dilutions'=>$run->bodDilutions->map(fn($d)=>$d->toArray())->values()->all(),
   'bod_controls'=>$run->bodControls->map(fn($q)=>$q->toArray())->values()->all(),
   'audit_trail'=>$run->audits->map(fn($a)=>$a->toArray())->values()->all()
  ];
 }
}
