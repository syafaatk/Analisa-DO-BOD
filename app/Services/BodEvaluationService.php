<?php
namespace App\Services;
class BodEvaluationService {
 public const BLANK_MAX=0.4;
 public const GGA_TARGET=198.0;
 public const GGA_TOLERANCE=30.5;
 public const RPD_MAX=30.0;
 public function evaluate(float $a1,float $a2,float $b1,float $b2,float $p): array {
  $sample=$a1-$a2;$blank=$b1-$b2;
  return ['sample_depletion'=>round($sample,6),'blank_depletion'=>round($blank,6),'sample_status'=>$sample>=2?'PASS':'FAIL','blank_status'=>($blank>=0&&$blank<self::BLANK_MAX)?'PASS':'FAIL','dilution_factor'=>$p>0?1/$p:null];
 }
 public function evaluateDilution(float $doInitial,float $doFinal,float $incubationTemperature,float $incubationHours): array {
  $depletion=$doInitial-$doFinal;
  $tempOk=$incubationTemperature>=19 && $incubationTemperature<=21;
  $timeOk=$incubationHours>=114 && $incubationHours<=126;
  $valid=$depletion>=2.0 && $doFinal>=1.0 && $tempOk && $timeOk;
  return ['depletion'=>round($depletion,6),'do_residual_ok'=>$doFinal>=1,'depletion_ok'=>$depletion>=2,'temperature_ok'=>$tempOk,'incubation_time_ok'=>$timeOk,'status'=>$valid?'PASS':'FAIL'];
 }
 public function evaluateGga(float $result): array {
  $min=self::GGA_TARGET-self::GGA_TOLERANCE;$max=self::GGA_TARGET+self::GGA_TOLERANCE;
  return ['result'=>$result,'target'=>self::GGA_TARGET,'lower'=>$min,'upper'=>$max,'status'=>$result>=$min&&$result<=$max?'PASS':'FAIL'];
 }
 public function rpd(float $x1,float $x2): float { $den=($x1+$x2)/2; return $den==0?0.0:abs($x1-$x2)/$den*100; }
 public function storageStatus(?float $hours): string {
  if($hours===null)return 'NOT_RECORDED';
  if($hours<=24)return 'PASS';
  return 'FAIL';
 }
}
