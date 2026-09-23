<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\Concerns\HasUuids;
class ReportNotification extends Model {use HasUuids;protected $fillable=['laboratory_id','analysis_run_id','client_id','recipient','type','status','sent_at','error'];protected $casts=['sent_at'=>'datetime'];}