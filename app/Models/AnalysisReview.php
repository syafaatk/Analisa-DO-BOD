<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToLaboratory;
class AnalysisReview extends Model
{
 use BelongsToLaboratory;
 protected $fillable=['laboratory_id','analysis_run_id','reviewer','decision','comments','reviewed_at'];
 protected $casts=['reviewed_at'=>'datetime'];
 public function analysis(){return $this->belongsTo(AnalysisRun::class,'analysis_run_id');}
}
