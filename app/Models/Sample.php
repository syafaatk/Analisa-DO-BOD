<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Concerns\BelongsToLaboratory;
class Sample extends Model {
 use HasUuids;
 use BelongsToLaboratory;
 protected $fillable=['laboratory_id','sample_code','sample_name','matrix','received_at','customer','notes'];
 protected $casts=['received_at'=>'datetime'];
 public function analysisRuns(){return $this->hasMany(AnalysisRun::class,'sample_code','sample_code');}
}
