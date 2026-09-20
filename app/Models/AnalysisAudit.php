<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AnalysisAudit extends Model {
 protected $fillable=['analysis_run_id','actor','action','from_status','to_status','changes','notes'];
 protected $casts=['changes'=>'array'];
}
