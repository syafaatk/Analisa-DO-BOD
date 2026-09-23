<?php
namespace App\Services;

class QcService
{
 public function rpd(float $a,float $b): float { $m=($a+$b)/2; return $m==0?0:abs($a-$b)/$m*100; }
 public function withinRpd(float $a,float $b,float $limit=10): array { $rpd=$this->rpd($a,$b); return ['rpd'=>$rpd,'limit'=>$limit,'status'=>$rpd<=$limit?'PASS':'FAIL']; }
 public function bodBlankDepletion(float $b1,float $b2): array { $d=$b1-$b2; return ['depletion'=>$d,'status'=>$d>=0?'PASS':'FAIL']; }
 public function bodSampleDepletion(float $a1,float $a2): array { $d=$a1-$a2; return ['depletion'=>$d,'status'=>$d>0?'PASS':'FAIL']; }
}
