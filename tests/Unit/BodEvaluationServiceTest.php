<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\Services\BodEvaluationService;
class BodEvaluationServiceTest extends TestCase {
 public function test_blank_limit():void{$r=(new BodEvaluationService)->evaluate(7,4.5,7,6.7,0.1);$this->assertSame('PASS',$r['blank_status']);$this->assertSame('PASS',$r['sample_status']);}
 public function test_dilution_acceptance():void{$r=(new BodEvaluationService)->evaluateDilution(8,5.5,20,120);$this->assertSame('PASS',$r['status']);}
 public function test_gga_acceptance():void{$r=(new BodEvaluationService)->evaluateGga(198);$this->assertSame('PASS',$r['status']);$this->assertSame(167.5,$r['lower']);$this->assertSame(228.5,$r['upper']);}
 public function test_rpd_limit_is_thirty_percent():void{$this->assertEqualsWithDelta(28.571428,(new BodEvaluationService)->rpd(10,14.999999),0.0001);}
}
