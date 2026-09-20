<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\Services\LaboratoryCalculationService;
class LaboratoryCalculationServiceTest extends TestCase{
 public function test_do_formula():void{$r=(new LaboratoryCalculationService)->dissolvedOxygen(1.2,0.025,300,1,1,50);$this->assertEqualsWithDelta(48.096321,$r['result'],0.000001);}
 public function test_bod_formula():void{$r=(new LaboratoryCalculationService)->bod5(7.8,2.8,7.7,6.7,2,1,0.1);$this->assertEqualsWithDelta(40,$r['result'],0.000001);}
 public function test_top_down_combination():void{$r=(new LaboratoryCalculationService)->topDownUncertainty(1.0,10,0.5,4,2);$this->assertEqualsWithDelta(2.062019,$r['expanded_uncertainty'],0.000001);}
}