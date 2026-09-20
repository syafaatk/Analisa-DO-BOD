<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UncertaintyComponent extends Model {
 protected $fillable=['uncertainty_model_id','code','name','source_type','distribution','standard_uncertainty','sensitivity_coefficient','contribution','degrees_of_freedom','relative_contribution_percent','observations'];
 protected $casts=['observations'=>'array'];
 public function model(){return $this->belongsTo(UncertaintyModel::class,'uncertainty_model_id');}
}
