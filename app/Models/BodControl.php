<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Concerns\BelongsToLaboratory;
class BodControl extends Model {
 use HasUuids;
 use BelongsToLaboratory;
 protected $fillable=['laboratory_id','analysis_run_id','control_type','control_code','do_initial','do_final','bod_result','expected_min','expected_max','acceptance_percent','status','notes'];
 protected $casts=['do_initial'=>'float','do_final'=>'float','bod_result'=>'float','expected_min'=>'float','expected_max'=>'float','acceptance_percent'=>'float'];
}
