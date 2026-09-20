<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AnalysisRun extends Model
{
 protected $fillable=['sample_code','parameter','method_version','analyst','analysed_at','inputs','calculation','uncertainty','notes'];
 protected $casts=['inputs'=>'array','calculation'=>'array','uncertainty'=>'array','analysed_at'=>'datetime'];
}
