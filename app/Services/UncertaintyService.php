<?php
namespace App\Services;

use InvalidArgumentException;

class UncertaintyService
{
 public function fromReplicates(array $values): array
 {
  $values=array_values(array_filter($values,fn($v)=>is_numeric($v)));
  $n=count($values);
  if($n<2) throw new InvalidArgumentException('Minimal 2 data observasi diperlukan.');
  $mean=array_sum($values)/$n;
  $ss=0.0; foreach($values as $v) $ss+=(($v-$mean)**2);
  $sd=sqrt($ss/($n-1));
  $se=$sd/sqrt($n);
  return ['n'=>$n,'mean'=>$mean,'sd'=>$sd,'standard_uncertainty'=>$sd,'standard_error'=>$se,'rsd_percent'=>$mean==0?null:abs($sd/$mean*100)];
 }

 public function build(array $components,float $coverageFactor=2.0): array
 {
  if($coverageFactor<=0) throw new InvalidArgumentException('Coverage factor harus lebih besar dari 0.');
  $rows=[];$sum=0.0;
  foreach($components as $component){
   $u=(float)($component['u']??0); $c=(float)($component['sensitivity']??1);
   $contribution=abs($c*$u); $sum+=($contribution**2);
   $rows[]=['name'=>$component['name']??'Component','u'=>$u,'sensitivity'=>$c,'contribution'=>$contribution,'variance'=>$contribution**2];
  }
  $uc=sqrt($sum); $expanded=$coverageFactor*$uc;
  foreach($rows as &$row){$row['relative_contribution_percent']=$sum==0?0:($row['variance']/$sum*100);}
  return ['components'=>$rows,'u_combined'=>$uc,'coverage_factor'=>$coverageFactor,'expanded_uncertainty'=>$expanded];
 }
}
