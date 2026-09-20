<?php
namespace App\Services;
class MethodValidationService
{
 public function doRun(float $do): array
 {
  return ['do'=>$do,'status'=>$do>0?'PASS':'FAIL','checks'=>['positive_result'=>$do>0]];
 }
 public function bodRun(float $a1,float $a2,float $b1,float $b2): array
 {
  $sample=$a1-$a2;$blank=$b1-$b2;
  return ['sample_depletion'=>$sample,'blank_depletion'=>$blank,'checks'=>['sample_depletion_positive'=>$sample>0,'blank_nonnegative'=>$blank>=0],'status'=>($sample>0&&$blank>=0)?'PASS':'CHECK'];
 }
 public function controlRange(float $value,float $min,float $max): array
 {
  return ['value'=>$value,'min'=>$min,'max'=>$max,'status'=>($value>=$min&&$value<=$max)?'PASS':'FAIL'];
 }
}
