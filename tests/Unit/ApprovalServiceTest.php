<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\Models\AnalysisRun;
use App\Services\ApprovalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
class ApprovalServiceTest extends TestCase
{
 use RefreshDatabase;
 private function makeRun(string $status='DRAFT'):AnalysisRun{
  return AnalysisRun::create(['sample_code'=>'TEST-001','parameter'=>'DO','method_version'=>'SNI 06-6989.14-2004','inputs'=>[],'calculation'=>[],'status'=>$status]);
 }
 public function test_approval_changes_analysis_status():void{
  $run=$this->makeRun('READY_FOR_REVIEW');
  app(ApprovalService::class)->review($run,'Reviewer','APPROVED','OK');
  $this->assertSame('APPROVED',$run->fresh()->status);
  $this->assertNotEmpty($run->fresh()->report_number);
 }
 public function test_review_rejects_non_ready_status():void{
  $run=$this->makeRun('DRAFT');
  $this->expectException(InvalidArgumentException::class);
  app(ApprovalService::class)->review($run,'Reviewer','APPROVED','OK');
 }
 public function test_reject_sets_status():void{
  $run=$this->makeRun('READY_FOR_REVIEW');
  app(ApprovalService::class)->review($run,'Reviewer','REJECTED','UL');
  $this->assertSame('REJECTED',$run->fresh()->status);
 }
}