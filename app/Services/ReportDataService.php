<?php
namespace App\Services;
use App\Models\AnalysisRun;
class ReportDataService
{
 public function result(int $id): array
 {
  $run=AnalysisRun::findOrFail($id);
  return ['sample_code'=>$run->sample_code,'parameter'=>$run->parameter,'method'=>$run->method_version,'analyst'=>$run->analyst,'analysed_at'=>$run->analysed_at?->toIso8601String(),'inputs'=>$run->inputs,'calculation'=>$run->calculation,'uncertainty'=>$run->uncertainty];
 }
}
