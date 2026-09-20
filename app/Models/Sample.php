<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Sample extends Model {
 protected $fillable=['sample_code','sample_name','matrix','received_at','customer','notes'];
 protected $casts=['received_at'=>'datetime'];
 public function analysisRuns(){return $this->hasMany(AnalysisRun::class,'sample_code','sample_code');}
}
