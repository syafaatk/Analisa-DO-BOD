<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Concerns\BelongsToLaboratory;
class BodDilution extends Model {
 use HasUuids;
 use BelongsToLaboratory;
 protected $fillable=['laboratory_id','analysis_run_id','sample_volume_ml','final_volume_ml','p','do_initial','do_final','do_depletion','dilution_code','dilution_at','do_initial_at','do_final_at','diluent_volume_ml','incubation_temperature','incubation_hours','selection_status'];
 protected $casts=['dilution_at'=>'datetime','do_initial_at'=>'datetime','do_final_at'=>'datetime','sample_volume_ml'=>'float','final_volume_ml'=>'float','p'=>'float','do_initial'=>'float','do_final'=>'float','do_depletion'=>'float','diluent_volume_ml'=>'float','incubation_temperature'=>'float','incubation_hours'=>'float'];
}
