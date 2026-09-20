<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\Models\AnalysisRun;
use App\Services\ApprovalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
class ApprovalServiceTest extends TestCase
{
 use RefreshDatabase;
 public function test_approval_changes_analysis_status():void{
  $run=AnalysisRun::create(['sample_code'=>'TEST-001','parameter'=>'DO','method_version'=>'SNI 06-6989.14-2004','inputs'=>[],'calculation'=>[]]);
  (new ApprovalService)->review($run,'Reviewer','APPROVED','OK');
  $this->assertSame('APPROVED',$run->fresh()->status);
 }
}
