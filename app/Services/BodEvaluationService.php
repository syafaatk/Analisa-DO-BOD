<?php
namespace App\Services;
class BodEvaluationService
{
 public function evaluate(float $a1,float $a2,float $b1,float $b2,float $p): array
 {
  $sample=$a1-$a2;$blank=$b1-$b2;
  return ['sample_depletion'=>$sample,'blank_depletion'=>$blank,'sample_status'=>$sample>0?'PASS':'FAIL','blank_status'=>$blank>=0?'PASS':'CHECK','dilution_factor'=>$p>0?1/$p:null];
 }
 public function multipleDilutions(array $results): array
 {
  $valid=array_values(array_filter($results,fn($x)=>is_numeric($x)&&$x>=0));
  if(!$valid)return ['count'=>0,'mean'=>null,'sd'=>null];
  $n=count($valid);$mean=array_sum($valid)/$n;
  $sd=$n>1?sqrt(array_sum(array_map(fn($v)=>($v-$mean)**2,$valid))/($n-1)):0;
  return ['count'=>$n,'mean'=>$mean,'sd'=>$sd,'rsd_percent'=>$mean==0?null:abs($sd/$mean*100)];
 }
}
