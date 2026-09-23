<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\Services\LaboratoryCalculationService;
class LaboratoryCalculationServiceTest extends TestCase{
 public function test_do_formula():void{$r=(new LaboratoryCalculationService)->dissolvedOxygen(1.2,1.2,0.025,250,1,1,50);$this->assertEqualsWithDelta(4.84,$r['result'],0.000001);}
 public function test_do_duo_structure():void{$c=(new LaboratoryCalculationService)->dissolvedOxygenDuo(1.71,1.77,1.74,1.76,0.025,250,1,1,50);$this->assertArrayHasKey('simplo',$c);$this->assertArrayHasKey('duplo',$c);$this->assertArrayHasKey('last_result',$c);$this->assertArrayHasKey('simplo_average',$c['simplo']);$this->assertArrayHasKey('duplo_average',$c['duplo']);$this->assertEquals(1.74,$c['simplo']['simplo_average']);$this->assertEquals(1.75,$c['duplo']['duplo_average']);$this->assertEqualsWithDelta(round(($c['simplo']['simplo_result']+$c['duplo']['duplo_result'])/2,2),$c['last_result'],0.000001);$this->assertContains($c['qc_status'],['PASS','REVIEW']);}
 public function test_bod_formula():void{$r=(new LaboratoryCalculationService)->bod5(7.8,2.8,7.7,6.7,2,1,0.1);$this->assertEqualsWithDelta(45,$r['result'],0.000001);}
 public function test_top_down_combination():void{$r=(new LaboratoryCalculationService)->topDownUncertainty(1.0,10,0.5,4,2);$this->assertEqualsWithDelta(2.061552813,$r['expanded_uncertainty'],0.000001);}
}