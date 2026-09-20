<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BodControl extends Model {
 protected $fillable=['analysis_run_id','control_type','control_code','do_initial','do_final','bod_result','expected_min','expected_max','acceptance_percent','status','notes'];
 protected $casts=['do_initial'=>'float','do_final'=>'float','bod_result'=>'float','expected_min'=>'float','expected_max'=>'float','acceptance_percent'=>'float'];
}
