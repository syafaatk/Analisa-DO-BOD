<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class ClientReportAccess extends Model { use HasUuids; protected $fillable=['laboratory_id','analysis_run_id','client_user_id','action','ip_address','user_agent','accessed_at']; protected $casts=['accessed_at'=>'datetime']; }