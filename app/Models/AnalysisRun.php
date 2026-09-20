<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AnalysisRun extends Model
{
 public function bodDilutions(){return $this->hasMany(BodDilution::class,'analysis_run_id');}
 public function bodControls(){return $this->hasMany(BodControl::class,'analysis_run_id');}
 public function audits(){return $this->hasMany(AnalysisAudit::class,'analysis_run_id');}
 protected $fillable=['sample_code','parameter','method_version','analyst','analysed_at','inputs','calculation','uncertainty','notes','status','approved_by','approved_at','sample_matrix','batch_code','instrument_code','reagent_lot','report_number','submitted_at'];
 protected $casts=['inputs'=>'array','calculation'=>'array','uncertainty'=>'array','analysed_at'=>'datetime','approved_at'=>'datetime','submitted_at'=>'datetime'];
}
