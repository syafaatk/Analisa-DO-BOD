<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AnalysisReview extends Model
{
 protected $fillable=['analysis_run_id','reviewer','decision','comments','reviewed_at'];
 protected $casts=['reviewed_at'=>'datetime'];
 public function analysis(){return $this->belongsTo(AnalysisRun::class,'analysis_run_id');}
}
