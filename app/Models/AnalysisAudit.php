<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToLaboratory;
class AnalysisAudit extends Model {
 use BelongsToLaboratory;
 protected $fillable=['laboratory_id','analysis_run_id','actor','action','from_status','to_status','changes','notes'];
 protected $casts=['changes'=>'array'];
}
