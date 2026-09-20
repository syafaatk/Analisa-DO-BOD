<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\Services\UncertaintyService;
class UncertaintyServiceTest extends TestCase
{
 public function test_statistics_from_replicates():void{
  $r=(new UncertaintyService)->fromReplicates([10,11,9,10]);
  $this->assertSame(4,$r['n']);
  $this->assertEqualsWithDelta(10,$r['mean'],0.000001);
  $this->assertEqualsWithDelta(0.81649658,$r['sd'],0.000001);
 }
 public function test_budget_combines_components():void{
  $r=(new UncertaintyService)->build([['name'=>'precision','u'=>1,'sensitivity'=>1],['name'=>'bias','u'=>0.5,'sensitivity'=>1]],2);
  $this->assertEqualsWithDelta(1.11803399,$r['u_combined'],0.000001);
  $this->assertEqualsWithDelta(2.23606798,$r['expanded_uncertainty'],0.000001);
 }
}
