<?php
namespace App\Services;
use InvalidArgumentException;
class LaboratoryCalculationService
{
    public function dissolvedOxygen(float $titrasi1,float $titrasi2,float $normality,float $winklerVolumeMl,float $reagentMnso4Ml=1.0,float $reagentAlkaliMl=1.0,float $aliquotMl=50.0): array
    {
        foreach([$titrasi1,$titrasi2,$normality,$winklerVolumeMl,$aliquotMl] as $v) if($v<=0) throw new InvalidArgumentException('Input DO harus lebih besar dari 0.');
        $den=$winklerVolumeMl-$reagentMnso4Ml-$reagentAlkaliMl;
        if($den<=0) throw new InvalidArgumentException('Volume botol harus lebih besar dari total volume pereaksi.');
        $factor=$winklerVolumeMl/$den;
        $vAvg=($titrasi1+$titrasi2)/2;
        $do=($vAvg*$normality*8000.0*$factor)/$aliquotMl;
        return ['result'=>round($do,6),'v_avg'=>round($vAvg,6),'factor'=>round($factor,8),'formula'=>'V × N × 8000 × F / aliquot'];
    }
    public function dissolvedOxygenDuo(float $simploT1,float $simploT2,float $duploT1,float $duploT2,float $normality,float $winklerVolumeMl,float $reagentMnso4Ml=1.0,float $reagentAlkaliMl=1.0,float $aliquotMl=50.0): array
    {
        $s=$this->dissolvedOxygen($simploT1,$simploT2,$normality,$winklerVolumeMl,$reagentMnso4Ml,$reagentAlkaliMl,$aliquotMl);
        $d=$this->dissolvedOxygen($duploT1,$duploT2,$normality,$winklerVolumeMl,$reagentMnso4Ml,$reagentAlkaliMl,$aliquotMl);
        $rpd=$this->rpd($s['result'],$d['result']);
        return [
            'rpd'=>$rpd,
            'simplo'=>['factor'=>$s['factor'],'simplo_result'=>$s['result'],'formula'=>$s['formula']],
            'duplo'=>['factor'=>$d['factor'],'duplo_result'=>$d['result'],'formula'=>$d['formula']],
            'last_result'=>round(($s['result']+$d['result'])/2,6),
            'formula'=>'(simplo_result + duplo_result) / 2',
            'qc_status'=>$rpd<=10?'PASS':'REVIEW',
        ];
    }
    public function bod5(float $a1,float $a2,float $b1,float $b2,float $vb,float $vc,float $p): array
    {
        if($p<=0) throw new InvalidArgumentException('P harus lebih besar dari 0.');
        if($vb<0||$vc<0) throw new InvalidArgumentException('Volume suspensi mikroba tidak boleh negatif.');
        $sampleDepletion=$a1-$a2;
        $seedCorrection=$vb==0?0.0:(($b1-$b2)/$vb)*$vc;
        $bod=($sampleDepletion-$seedCorrection)/$p;
        return ['result'=>round($bod,6),'sample_depletion'=>round($sampleDepletion,6),'seed_correction'=>round($seedCorrection,6),'formula'=>'((A1-A2)-((B1-B2)/VB)×Vc)/P'];
    }
    public function topDownUncertainty(float $precisionSd,int $precisionN,?float $biasSd=null,?int $biasN=null,float $coverageFactor=2.0): array
    {
        if($precisionSd<0||$precisionN<2) throw new InvalidArgumentException('Data precision tidak valid.');
        $uPrecision=$precisionSd;
        $uBias=($biasSd!==null&&$biasN!==null&&$biasN>=2)?$biasSd/sqrt($biasN):0.0;
        $uc=sqrt(($uPrecision**2)+($uBias**2));
        return ['u_precision'=>round($uPrecision,8),'u_bias'=>round($uBias,8),'u_combined'=>round($uc,8),'coverage_factor'=>$coverageFactor,'expanded_uncertainty'=>round($coverageFactor*$uc,8)];
    }
    public function rpd(float $x1,float $x2): float { $den=($x1+$x2)/2; return $den==0?0.0:abs($x1-$x2)/$den*100; }
}
