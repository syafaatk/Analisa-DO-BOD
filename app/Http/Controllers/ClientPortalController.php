<?php
namespace App\Http\Controllers;
use App\Models\AnalysisRun;
use App\Models\ClientReportAccess;
use Illuminate\Http\Request;
class ClientPortalController extends Controller {
 private function user(Request $r){return $r->session()->get('client_user');}
 public function dashboard(Request $r){$u=$this->user($r);$reports=AnalysisRun::where('client_id',$u['client_id'])->where('status','APPROVED')->latest('approved_at')->paginate(15);return view('client.dashboard',['reports'=>$reports]);}
 public function report(Request $r,AnalysisRun $analysis){
  $u=$this->user($r);
  abort_unless($analysis->client_id===$u['client_id'] && $analysis->status==='APPROVED',404);
  ClientReportAccess::create(['laboratory_id'=>$u['laboratory_id'],'analysis_run_id'=>$analysis->id,'client_user_id'=>$u['id'],'action'=>'VIEW','ip_address'=>$r->ip(),'user_agent'=>$r->userAgent()]);
  return view('client.report',['report'=>$this->safeReport($analysis)]);
 }
 public function download(Request $r,AnalysisRun $analysis){
  $u=$this->user($r);
  abort_unless($analysis->client_id===$u['client_id'] && $analysis->status==='APPROVED',404);
  ClientReportAccess::create(['laboratory_id'=>$u['laboratory_id'],'analysis_run_id'=>$analysis->id,'client_user_id'=>$u['id'],'action'=>'PRINT','ip_address'=>$r->ip(),'user_agent'=>$r->userAgent()]);
  return view('client.report',['report'=>$this->safeReport($analysis),'autoPrint'=>true]);
 }
 private function safeReport(AnalysisRun $a){return ['id'=>$a->id,'report_number'=>$a->report_number,'sample_code'=>$a->sample_code,'parameter'=>$a->parameter,'method_version'=>$a->method_version,'analysed_at'=>$a->analysed_at?->format('Y-m-d H:i'),'approved_at'=>$a->approved_at?->format('Y-m-d H:i'),'analyst'=>$a->analyst,'sample_matrix'=>$a->sample_matrix,'calculation'=>$a->calculation,'client'=>$a->client?->name,'bod_dilutions'=>$a->bodDilutions()->get(['dilution_code','do_depletion','incubation_temperature','incubation_hours','selection_status'])->toArray()];}
}